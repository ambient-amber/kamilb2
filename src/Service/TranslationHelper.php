<?php

namespace App\Service;

class TranslationHelper
{
    private $curl;
    const YANDEX_KEY = 'trnsl.1.1.20200101T184813Z.a1c858743c53384d.ea91f9c2af9e3f79f363340b03af5edaebc4625b';

    public function __construct(CurlHelper $curlHelper)
    {
        $this->curl = $curlHelper;
    }

    public function translate($content, $format = 'html', $lang = 'ru-uz')
    {
        $translationResult = false;

        if (!empty($content)) {
            $translationResult = $this->curl->connect(
                'https://translate.yandex.net/api/v1.5/tr.json/translate',
                [
                    'key' => self::YANDEX_KEY,
                    'lang' => $lang,
                    'format' => $format,
                    'text' => $content
                ],
                true,
                ['Content-Type: application/x-www-form-urlencoded']
            );
        }

        return $translationResult;
    }
}
