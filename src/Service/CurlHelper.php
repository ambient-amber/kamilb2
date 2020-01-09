<?php

namespace App\Service;

class CurlHelper
{
    public function connect($url, $post = [], $httpBuildQuery = false, $contentType = '')
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);

        if (!empty($contentType)) {
            curl_setopt($curl, CURLOPT_HTTPHEADER, $contentType);
        }

        if (!empty($post)) {
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($httpBuildQuery) {
                curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($post,'','&'));
            } else {
                curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
            }
        }

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, true);
    }
}
