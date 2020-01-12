<?php

namespace App\Service;

use Symfony\Component\DomCrawler\Crawler;
use App\Service\UploadHelper;

class ParserHelper
{
    private $uploadHelper;

    public function __construct(UploadHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }

    private $siteTags = [
        'cosmo.ru' => [
            'title_selector' => 'h1',
            'preview_text_selector' => '.article-lead',
            'main_image_selector' => '.article-img',
            'content_selector' => '.article-body',
            'content_image_list_div_class' => 'article-pic-list',
            'image_div_class' => 'article-pic'
        ]
    ];

    public function parsePage($url, $site)
    {
        $result = [
            'title' => '',
            'preview_content' => '',
            'content' => '',
            'image_hash' => '',
            'image_name' => '',
        ];

        $html = file_get_contents($url);

        if ($html) {
            $crawler = new Crawler($html);

            $tags = isset($this->siteTags[$site]) ? $this->siteTags[$site] : false;

            if ($tags) {
                // Заголовок
                $title = $crawler->filter($tags['title_selector']);
                $result['title'] = ($title->count()) ? $title->text() : '';

                // Текст превью
                $previewText = $crawler->filter($tags['preview_text_selector']);
                $result['preview_content'] = ($previewText->count()) ? $previewText->text() : '';

                // Главная картинка
                $mainImage = $crawler->filter($tags['main_image_selector']);

                if ($mainImage->count()) {
                    $mainImageSrc = $mainImage->attr('src');
                    $fileUploadResult = $this->uploadHelper->uploadExternalFile($mainImageSrc);

                    if (!empty($fileUploadResult)) {
                        $result['image_hash'] = $fileUploadResult['hash_name'];
                        $result['image_name'] = $fileUploadResult['original_name'];
                    }
                }

                // Контент
                $content = $crawler->filter($tags['content_selector']);

                foreach ($content->children() as $contentElement) {
                    $contentElementCrawler = new Crawler($contentElement);

                    switch ($contentElement->nodeName) {
                        case 'h2':
                            $text = $contentElementCrawler->text();

                            if (!empty($text)) {
                                $result['content'] .= '<h2>' . $contentElementCrawler->text() . '</h2>';
                            }

                            break;
                        case 'p':
                            $text = $contentElementCrawler->text();

                            if (!empty($text)) {
                                $result['content'] .= '<p>' . $contentElementCrawler->text() . '</p>';
                            }

                            break;
                        case 'div':
                            if ($contentElement->hasAttribute('class')) {
                                if (strpos($contentElement->getAttribute('class'), $tags['image_div_class']) !== false) {
                                    // Блок с картинкой
                                    $contentElementCrawler->filter('img')->each(function (Crawler $node) use(&$result) {
                                        $imagePathData = $node->attr('data-original');
                                        $imagePathSrc = $node->attr('src');
                                        $imageSrc = empty($imagePathData) ? $imagePathSrc : $imagePathData;

                                        $fileUploadResult = $this->uploadHelper->uploadExternalFile($imageSrc);

                                        if (!empty($fileUploadResult)) {
                                            $uploadedFilePath = $this->uploadHelper->getPublicHashPath($fileUploadResult['hash_name']);
                                            $result['content'] .= '<p><img src="' . $uploadedFilePath . '"/></p>';
                                        }
                                    });
                                }
                            }
                            break;
                        case 'ul':
                            if (strpos($contentElement->getAttribute('class'), $tags['content_image_list_div_class']) !== false) {
                                // Блок со списком картинок
                                $result['content'] .= '<div class="images_list">';
                                $contentElementCrawler->filter('img')->each(function (Crawler $node) use(&$result) {
                                    $imagePathData = $node->attr('data-original');
                                    $imagePathSrc = $node->attr('src');
                                    $imageSrc = empty($imagePathData) ? $imagePathSrc : $imagePathData;

                                    $fileUploadResult = $this->uploadHelper->uploadExternalFile($imageSrc);

                                    if (!empty($fileUploadResult)) {
                                        $uploadedFilePath = $this->uploadHelper->getPublicHashPath($fileUploadResult['hash_name']);
                                        $result['content'] .= '<div class="images_list_item"><img src="' . $uploadedFilePath . '"></div>';
                                    }
                                });
                                $result['content'] .= '</div>';
                            }
                            break;
                    }
                }
            }
        }

        return $result;
    }
}
