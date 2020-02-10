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
                    $templateValue = isset($templateValues[$template]) ? $templateValues[$template] : '';

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