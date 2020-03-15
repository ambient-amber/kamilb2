<?php

namespace App\Service;

use App\Repository\MetaDataRepository;
use Symfony\Component\HttpFoundation\Request;

class MetaDataHelper
{
    private $metaDataRepository;
    private $uploadHelper;
    private $delimiter = '#';

    public function __construct(MetaDataRepository $metaDataRepository, UploadHelper $uploadHelper)
    {
        $this->metaDataRepository = $metaDataRepository;
        $this->uploadHelper = $uploadHelper;
    }

    /**
     * Получение title, description, keys по ссылке.
     * Из ссылки удаляется упоминание языка. Элементы мета даты связаны через колонку в БД.
     * Мета данные могут быть указаны явными значенниями или шаблонами:
     * #title#, #description#, #category_title#, #category_description#
     * При подстановке из значений удаляются html теги.
     *
     * Получение полного пути до изображения og:image, включая протокол и домен, (сейчас только у статей)
     *
     * Ссылка может быть статическим значением или регулярным выражением.
     * Например ^/article/.*[^/]/(\?.*)? с шаблонами для всех категорий статей
     * Или /page/polzovatelcskoe-soglashenie/ с явными значениями.
     * Если мета теги не были найдены по ссылке с get параметрами, то будет второй поиск без get параметров
     *
     * @param Request $request
     * @param array $options Массив значений от сущности для подстановки в шаблоны
     * и параметры для формирования значений мета тегов
     *
     * @return array.
     */
    public function getMetaData(Request $request, $options = [])
    {
        $uri = $request->getRequestUri();

        $uri = preg_replace('/^\/' . $request->getLocale() . '/', '', $uri);

        $metaData = $this->metaDataRepository->findActiveByUrl($uri, $request->getLocale());

        if (empty($metaData)) {
            $uri = preg_replace('/\?.*/', '', $uri);

            $metaData = $this->metaDataRepository->findActiveByUrl($uri, $request->getLocale());
        }

        if (!empty($metaData) && $metaData['is_template']) {
            $metaData = $this->metaDataTemplateReplacement($metaData, $options);
        }

        if (!empty($options['image_hash']) && !empty($options['domain']) && !empty($options['protocol'])) {
            $metaData['og_image'] = $options['protocol'] . '://' . $options['domain'] . $this->uploadHelper->getPublicHashPath($options['image_hash']);
        }

        return $metaData;
    }

    public function metaDataTemplateReplacement($metaData, $options)
    {
        foreach ($metaData as $field => $value) {
            preg_match_all('/' . $this->delimiter . '(.*?)' . $this->delimiter . '/', $value, $templates);

            if (!empty($templates[1])) {
                foreach ($templates[1] as $k => $template) {
                    if (isset($options[$template])) {
                        $templateValue = preg_replace('/<.*?>/', '', $options[$template]);
                    } else {
                        $templateValue = '';
                    }

                    $value = str_replace($templates[0][$k], $templateValue, $value);
                }

                $metaData[$field] = $value;
            }
        }

        return $metaData;
    }

    public function setDelimiter(string $delimiter)
    {
        $this->delimiter = $delimiter;
    }
}