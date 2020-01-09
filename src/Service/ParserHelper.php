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
            'title' => 'h1',
            'preview_text' => '.article-lead',
            'image' => '.article-img',
            'content' => '.article-body',
            'image_div_class' => 'article-pic'
        ]
    ];

    public function parsePage($url, $site)
    {
        $result = [
            'title' => '',
            'preview_content' => '',
            'content' => '',
            'image_src' => '',
            'content_images' => [],
        ];

        $html = file_get_contents($url);

        if ($html) {
            $crawler = new Crawler($html);

            $tags = isset($this->siteTags[$site]) ? $this->siteTags[$site] : false;

            if ($tags) {
                $result['title'] = $crawler->filter($tags['title'])->text();
                $result['preview_content'] = $crawler->filter($tags['preview_text'])->text();
                $result['image_src'] = $crawler->filter($tags['image'])->attr('src');

                $content = $crawler->filter($tags['content']);

                foreach ($content->children() as $contentElement) {
                    $contentElementCrawler = new Crawler($contentElement);

                    switch ($contentElement->nodeName) {
                        case 'h2':
                            $result['content'] .= '<h2>' . $contentElementCrawler->text() . '</h2>';
                            break;
                        case 'p':
                            $result['content'] .= '<p>' . $contentElementCrawler->text() . '</p>';
                            break;
                        case 'div':
                            if ($contentElement->hasAttribute('class')
                                && strpos($contentElement->getAttribute('class'), $tags['image_div_class']) !== false
                            ) {
                                $contentElementCrawler->filter('img')->each(function (Crawler $node) use(&$result) {
                                    $result['content'] .= '<p><img src="' . $node->attr('data-original') . '"/></p>';
                                    $result['content_images'][] = $node->attr('data-original');
                                });
                            }
                            break;
                    }
                }
            }
        }

        return $result;
    }
}
