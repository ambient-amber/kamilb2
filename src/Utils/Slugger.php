<?php

namespace App\Utils;

class Slugger
{
    public function slugify(string $value): string
    {
        // ToDo подумать над вариантом получше
        $transliterator = \Transliterator::create('Any-Latin');
        $transliteratorToASCII = \Transliterator::create('Latin-ASCII');
        $value = $transliteratorToASCII->transliterate($transliterator->transliterate($value));

        return preg_replace(
            ['/\s+/', '/[^a-z0-9-]/i'],
            ['-', ''],
            mb_strtolower(trim(strip_tags($value)), 'UTF-8')
        );
    }
}
