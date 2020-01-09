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

    public function uploadExternalFile($url)
    {
        $fileData = @file_get_contents($url);

        if (!empty($fileData)) {
            /*$tmpFile = tmpfile();
            $tmpPath = stream_get_meta_data($tmpFile)['uri'];

            $file = new UploadedFile($tmpPath, basename($url));*/

            $tmpfile = tempnam(sys_get_temp_dir(), 'ext');
            file_put_contents($tmpfile, $fileData);

            echo '$tmpfile ' . $tmpfile . '<br/>';

            $file = new UploadedFile($tmpfile, basename($url));

            return $this->uploadHashFile($file);
        }
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
     * Удаление файла со сгенерированным названием.
     *
     * @param string $hashName Сгенерированное название
     *
     * @return void.
     */
    public function unloadHashFile($hashName)
    {
        // Получение пути до файла
        $fileNamePath = $this->parseHashFileNamePath($hashName);

        $fullPath = $this->uploadsHashPath . $fileNamePath . $hashName;

        $fullPathParts = pathinfo($fullPath);

        if (file_exists($fullPathParts['dirname'])) {
            $files = scandir($fullPathParts['dirname']);

            if ($files) {
                // Удаляем не только сам файл, но и его вариации с разными разрешениями
                // Например, d69df68135.jpeg и d69df68135_100_100.jpeg
                foreach ($files as $file) {
                    if (preg_match('/^' . $fullPathParts['filename'] . '/', $file)) {
                        unlink($fullPathParts['dirname'] . '/' . $file);
                    }
                }

                // Сканируем директории, в которых лежал файл, поднимаясь по вложенности вверх,
                // попутно удаляя директории, если они остались пустыми после удаления файла.
                // Например файл d69df68135.jpeg лежит в uploads/hash/d69/df6/d69df68135.jpeg.
                // Проверяется:
                // uploads/hash/d69/df6/
                // uploads/hash/d69/
                $fileNamePathParts = explode('/', $fileNamePath);
                $directory = $fullPathParts['dirname'];

                for ($i = (count($fileNamePathParts) - 1); $i > 0; $i--) {
                    $directory = preg_replace('/' . $fileNamePathParts[$i] . '(\/)?$/', '', $directory);
                    $directoryFiles = scandir($directory);

                    if ($directoryFiles) {
                        $directoryFiles = array_diff($directoryFiles, ['.', '..']);

                        if (!count($directoryFiles)) {
                            rmdir($directory);
                        }
                    }
                }
            }
        }
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
