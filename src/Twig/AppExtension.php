<?php

namespace App\Twig;

use Symfony\Contracts\Service\ServiceSubscriberInterface;
use Psr\Container\ContainerInterface;
use Twig\Extension\AbstractExtension;
use App\Service\UploadHelper;
use App\Service\ImageResizeHelper;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension implements ServiceSubscriberInterface
{
    private $container;
    private $projectDir;

    public function __construct(ContainerInterface $container, string $projectDir)
    {
        $this->container = $container;
        $this->projectDir = $projectDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
            new TwigFunction('uploaded_hash_asset', [$this, 'getUploadedHashAssetPath']),
            new TwigFunction('resize_image', [$this, 'getResizedImage']),
            new TwigFunction('resized_hash_image_html', [$this, 'getResizedHashImageHtml']),
            new TwigFunction('plural_ending', [$this, 'getPluralEnding']),
            new TwigFunction('svg_content', [$this, 'getSvgContent']),
        ];
    }

    /**
     * Получение публичного пути для сгенерированного названия файла
     *
     * @param string $fileName Сгенерированное название
     *
     * @return string
     */
    public function getUploadedHashAssetPath(string $fileName): string
    {
        return $this->container
            ->get(UploadHelper::class)
            ->getPublicHashPath($fileName);
    }

    /**
     * Получение публичного пути загруженного файла
     *
     * @param string $path Путь до файла
     *
     * @return string
     */
    public function getUploadedAssetPath(string $path): string
    {
        return $this->container
            ->get(UploadHelper::class)
            ->getPublicPath($path);
    }

    /**
     * Получение html изображения с измененными размерами.
     *
     * @param string $fileName Сгенерированное название
     * @param array $settings Опции изменения размеров изображения
     *
     * @return string Html img с изображением или svg с заглушкой
     */
    public function getResizedHashImageHtml(string $fileName, $settings)
    {
        $resizedImage = $this->container
            ->get(ImageResizeHelper::class)
            ->resizeHashImage($fileName, $settings);

        if ($resizedImage) {
            return '<img src="' . $resizedImage . '">';
        } else {
            return $this->getSvgContent('no_img.svg');
        }
    }

    /**
     * Получение окончания слова в зависимости от количества
     *
     * @param int $count Количество
     * @param string $wordVariants Формы слова минута|минуты|минут или bola|bolalar или city|cities
     *
     * @return string
     */
    public function getPluralEnding(int $count, string $wordVariants): string
    {
        $forms = explode('|', $wordVariants);
        $resultForm = $forms[0];

        if (count($forms) == 3) {
            if ($count % 10 === 1 && $count % 100 !== 11) {
                $resultForm = $forms[0];
            } else {
                if ($count % 10 >= 2 && $count % 10 <= 4 && ($count % 100 < 10 || $count % 100 >= 20)) {
                    $resultForm = $forms[1];
                } else {
                    $resultForm = $forms[2];
                }
            }
        } else if (count($forms) == 2 && $count > 1) {
            $resultForm = $forms[1];
        }

        return $resultForm;
    }

    public function getSvgContent($fileName, $dir = '/public/assets/img/')
    {
        return file_get_contents($this->projectDir . $dir . $fileName);
    }

    public static function getSubscribedServices()
    {
        return [
            UploadHelper::class,
            ImageResizeHelper::class,
        ];
    }
}
