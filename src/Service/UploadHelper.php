<?php

namespace App\Service;

use Symfony\Component\Asset\Context\RequestStackContext;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class UploadHelper
{
    public $uploadsFolder;
    public $uploadsHashFolder;

    public $uploadsPath;
    public $uploadsHashPath;
    private $requestStackContext;

    public function __construct(
        string $uploadsFolder,
        string $uploadsHashFolder,
        string $uploadsPath,
        string $uploadsHashPath,
        RequestStackContext $requestStackContext
    ) {
        /*
         * Метод $this->getParameter() можно использовать только в контроллере.
         * Если нужен параметр какого-нибудь конфига, то он добавляется внедрением зависимости.
         * В данном случае $uploadsHashFolder, $uploadsFolder, $uploadsPath, $uploadsHashPath - параметры
         * из config/services.yaml
         */
        $this->uploadsHashFolder = $uploadsHashFolder;
        $this->uploadsFolder = $uploadsFolder;
        $this->uploadsPath = $uploadsPath;
        $this->uploadsHashPath = $uploadsHashPath;

        $this->requestStackContext = $requestStackContext;
    }

    /**
     * Сохранение файла с генерацией уникального названия файла.
     *
     * @param UploadedFile $file Объект файла
     *
     * @return array Оригинальное и сгенерированное название.
     */
    public function uploadHashFile(UploadedFile $file)
    {
        $newFileNamePath = $this->generateUniqueName($file);

        return $this->uploadFile($file, $newFileNamePath['path'], $newFileNamePath['name']);
    }

    /**
     * Сохранение файла по известному пути с известным названием.
     *
     * @param UploadedFile $file Объект файла
     * @param string $path Заданный путь загрузки
     * @param string $name Заданное название файла
     *
     * @return array Оригинальное и сгенерированное название.
     */
    public function uploadFile(UploadedFile $file, $path, $name)
    {
        $file->move($path, $name);

        return [
            'original_name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'hash_name' => $name
        ];
    }

    /**
     * Создание уникального названия файла.
     *
     * @param UploadedFile $file Объект файла
     *
     * @return array Новое уникальное название и путь, который оно образует.
     */
    private function generateUniqueName(UploadedFile $file)
    {
        $fileExtension = $file->guessExtension();

        do {
            $uniqueName = md5(microtime() . rand(0, 9999)) . '.' . $fileExtension;
            $uniqueNamePath = $this->parseHashFileNamePath($uniqueName);

            $file = $this->uploadsHashPath . $uniqueNamePath . $uniqueName;
        } while (file_exists($file));

        return [
            'name' => $uniqueName,
            'path' => $this->uploadsHashPath . $uniqueNamePath
        ];
    }

    /**
     * Вычленение из названия файла пути до него
     *
     * @param string $fileName Сгенерированное название файла
     *
     * @return string При названии 13177bfb8587.jpeg вернет 131/77b/
     */
    public function parseHashFileNamePath($fileName)
    {
        $fileNameParts = str_split($fileName, 3);

        if (!empty($fileNameParts[0]) && !empty($fileNameParts[1])) {
            $path = $fileNameParts[0] . '/' . $fileNameParts[1] . '/';
        } else {
            throw new FileException('Could not parse path to file ' . $fileName);
        }

        return $path;
    }

    /**
     * Публичный путь до файла со сгенерированным названием
     *
     * @param string $fileName Сгенерированное название файла
     *
     * @return string вернет /uploads/hash/path/file.ext
     */
    public function getPublicHashPath($fileName): string
    {
        $fileNamePath = $this->parseHashFileNamePath($fileName);

        return $this->getPublicPath($this->uploadsHashFolder . '/' . $fileNamePath . $fileName);
    }

    /**
     * Публичный путь до файла
     *
     * @param string $path Полный путь до файла в папке uploads
     *
     * @return string вернет /uploads/path/file.ext
     */
    public function getPublicPath(string $path): string
    {
        // getBasePath needed if you deploy under a subdirectory
        return $this->requestStackContext->getBasePath() . '/' . $this->uploadsFolder . '/' . $path;
    }
}
