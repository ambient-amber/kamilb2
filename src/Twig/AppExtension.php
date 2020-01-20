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

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('uploaded_asset', [$this, 'getUploadedAssetPath']),
            new TwigFunction('uploaded_hash_asset', [$this, 'getUploadedHashAssetPath']),
            new TwigFunction('resize_image', [$this, 'getResizedImage']),
            new TwigFunction('resize_hash_image', [$this, 'getResizedHashImage']),
            new TwigFunction('plural_ending', [$this, 'getPluralEnding']),
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
     * Изменение размеров изображения со сгенерированным названием.
     *
     * @param string $fileName Сгенерированное название
     * @param array $settings Опции изменения размеров изображения
     *
     * @return string Путь до нового изображения с новыми размерами
     */
    public function getResizedHashImage(string $fileName, $settings): string
    {
        return $this->container
            ->get(ImageResizeHelper::class)
            ->resizeHashImage($fileName, $settings);
    }

    /**
     * Изменение размеров изображения
     *
     * @param string $path Путь до файла в папке uploads
     * @param array $settings Опции изменения размеров изображения
     *
     * @return string Путь до нового изображения с новыми размерами
     */
    public function getResizedImage(string $path, $settings): string
    {
        return $this->container
            ->get(ImageResizeHelper::class)
            ->resizeImage($path, $settings);
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

    public static function getSubscribedServices()
    {
        return [
            UploadHelper::class,
            ImageResizeHelper::class,
        ];
    }
}
