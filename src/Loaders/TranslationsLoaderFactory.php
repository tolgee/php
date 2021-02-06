<?php


namespace Tolgee\Core\Loaders;


use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Enums\TranslationsSources;
use Tolgee\Core\TolgeeConfig;

class TranslationsLoaderFactory
{
    /**
     * @var TolgeeConfig
     */
    private $config;

    public function __construct(TolgeeConfig $config)
    {
        $this->config = $config;
    }

    /**
     * @return TranslationsLoader
     */
    public function getLoader()
    {
        $isDevelopment = $this->config->mode === Modes::DEVELOPMENT;
        $isLoadFromApiEnabled = $this->config->developmentTranslationsSource === TranslationsSources::API;
        if ($isDevelopment && $isLoadFromApiEnabled) {
            return new ApiTranslationsLoader($this->config);
        }
        return new LocalFileTranslationsLoader($this->config);
    }
}