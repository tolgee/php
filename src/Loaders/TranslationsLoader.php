<?php


namespace Tolgee\Core\Loaders;


interface TranslationsLoader
{
    /**
     * @param $lang string
     * @return mixed[]
     */
    function getTranslations(string $lang): array;
}