<?php

namespace App\Service;

use Imagick;

class ImageResizeHelper
{
    private $uploadHelper;

    public function __construct(UploadHelper $uploadHelper)
    {
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Изменение размеров изображения со сгенерированным названием.
     *
     * @param string $fileName Путь до файла в папке uploads
     * @param array $settings Опции изменения размеров изображения
     *
     * @return string Путь до нового изображения с новыми размерами
     */
    public function resizeHashImage($fileName, $settings)
    {
        $filePath = $this->uploadHelper->uploadsHashFolder . '/';
        $filePath .= $this->uploadHelper->parseHashFileNamePath($fileName);
        $filePath .= $fileName;

        return $this->resizeImage($filePath, $settings);
    }

    /**
     * Изменение размеров изображения
     *
     * @param string $publicPath Путь до файла в папке uploads
     * @param array $settings Опции изменения размеров изображения
     *
     * @return string Путь до нового изображения с новыми размерами
     *
     * @throws
     */
    public function resizeImage($publicPath, $settings)
    {
        $width = empty($settings['width']) ? 0 : $settings['width'];
        $height = empty($settings['height']) ? 0 : $settings['height'];
        $method = empty($settings['method']) ? 'exact' : $settings['method'];
        $bgColor = empty($settings['bg_color']) ? 'white' : $settings['bg_color'];
        $ignoreExistence = empty($settings['ignore_existence']) ? false : $settings['ignore_existence'];

        $resultPublicPath = $publicPath;

        if ($width > 0 || $height > 0) {
            $fullPath = $this->uploadHelper->uploadsPath . $publicPath;

            $fullPathParts = pathinfo($fullPath);
            $dimensionsNamePart = '_' . $width . '_' . $height;

            $dimensionsImageName = $fullPathParts['filename'] . $dimensionsNamePart . '.' . $fullPathParts['extension'];
            $dimensionsImageNameFullPath = $fullPathParts['dirname'] . '/' . $dimensionsImageName;

            if (!file_exists($dimensionsImageNameFullPath) || $ignoreExistence) {
                $imagick = new Imagick($fullPath);

                switch ($method) {
                    case 'exact':
                        $imagick->resizeImage($width, $height, Imagick::FILTER_POINT, $blur = 1, true);

                        $newWidth = $imagick->getImageWidth();
                        $newHeight = $imagick->getImageHeight();

                        $offLeft = (($width - $newWidth) / 2) * -1;
                        $offTop = (($height - $newHeight) / 2) * -1;

                        $imagick->setImageBackgroundColor($bgColor);
                        $imagick->extentImage($width, $height, $offLeft, $offTop);

                        break;

                    case 'scale_best_fit':
                        $imagick->scaleImage($width, $height, true);

                        break;
                    case 'scale':
                        $imagick->scaleImage($width, $height);

                        break;
                }

                $imagick->writeImage($dimensionsImageNameFullPath);
            }

            $resultPublicPath = str_replace(
                $fullPathParts['basename'],
                $dimensionsImageName,
                $publicPath
            );
        }

        return $this->uploadHelper->getPublicPath($resultPublicPath);
    }
}
