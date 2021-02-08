<?php


namespace Tolgee\Core;


use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Enums\TranslationSources;

class TolgeeConfig
{
    public $mode = Modes::PRODUCTION;
    public $inputPrefix = '%-%tolgee:';
    public $inputSuffix = '%-%';
    public $apiUrl = "https://app.tolgee.io";
    public $apiKey = "";
    public $defaultLanguage = 'en';
    public $fallbackLanguage = "en";
    public $developmentTranslationsSource = TranslationSources::API;
    public $localFilesAbsolutePath = __DIR__ . "../../../../i18n";
}