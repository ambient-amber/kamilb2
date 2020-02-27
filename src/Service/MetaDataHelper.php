<?php

namespace App\Service;

use App\Repository\MetaDataRepository;
use Symfony\Component\HttpFoundation\Request;

class MetaDataHelper
{
    private $metaDataRepository;
    private $delimiter = '#';

    public function __construct(MetaDataRepository $metaDataRepository)
    {
        $this->metaDataRepository = $metaDataRepository;
    }

    /**
     * Получение title, description, keys по ссылке.
     * Из ссылки удаляется упоминание языка. Элементы мета даты связаны через колонку в БД.
     * Мета данные могут быть указаны явными значенниями или шаблонами:
     * #title#, #description#, #category_title#, #category_description#
     * При подстановке из значений удаляются html теги.
     *
     * Ссылка может быть статическим значением или регулярным выражением.
     * Например ^/article/.*[^/]/(\?.*)? с шаблонами для всех категорий статей
     * Или /page/polzovatelcskoe-soglashenie/ с явными значениями.
     *
     * @param Request $request
     * @param array $templateValues Массив значений от сущности для подстановки в шаблоны
     *
     * @return array.
     */
    public function getMetaData(Request $request, $templateValues = [])
    {
        $uri = $request->getRequestUri();

        $uri = preg_replace('/^\/' . $request->getLocale() . '/', '', $uri);

        $metaData = $this->metaDataRepository->findActiveByUrl($uri, $request->getLocale());

        if (!empty($metaData)) {
            if ($metaData['is_template']) {
                $metaData = $this->metaDataTemplateReplacement($metaData, $templateValues);
            }
        }

        return $metaData;
    }

    public function metaDataTemplateReplacement($metaData, $templateValues)
    {
        foreach ($metaData as $field => $value) {
            preg_match_all('/' . $this->delimiter . '(.*?)' . $this->delimiter . '/', $value, $templates);

            if (!empty($templates[1])) {
                foreach ($templates[1] as $k => $template) {
                    if (isset($templateValues[$template])) {
                        $templateValue = preg_replace('/<.*?>/', '', $templateValues[$template]);
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