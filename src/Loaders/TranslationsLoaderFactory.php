<?php


namespace Tolgee\Core\Loaders;


use GuzzleHttp\Client;
use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Enums\TranslationSources;
use Tolgee\Core\Exceptions\UnknownTranslationSourceException;
use Tolgee\Core\TolgeeConfig;

class TranslationsLoaderFactory
{
    /**
     * @var TolgeeConfig
     */
    private $config;
    /**
     * @var ApiTranslationsLoader|null
     */
    private $apiTranslationsLoader;

    public function __construct(TolgeeConfig $config,
                                ApiTranslationsLoader $apiTranslationsLoader = null)
    {
        $this->config = $config;
        $this->apiTranslationsLoader = $apiTranslationsLoader;
    }

    /**
     * @return ApiTranslationsLoader|null
     */
    public function getApiTranslationsLoader(): ?ApiTranslationsLoader
    {
        $this->apiTranslationsLoader = $this->apiTranslationsLoader ?:
            new ApiTranslationsLoader($this->config, new Client());
        return $this->apiTranslationsLoader;
    }

    /**
     * @return TranslationsLoader
     */
    public function getLoader()
    {
        $isDevelopment = $this->config->mode === Modes::DEVELOPMENT;

        if ($isDevelopment && $this->config->developmentTranslationsSource === TranslationSources::API) {
            return $this->getApiTranslationsLoader();
        }

        if (!$isDevelopment || $this->config->developmentTranslationsSource === TranslationSources::LOCAL_FILE) {
            return new LocalFileTranslationsLoader($this->config);
        }

        throw new UnknownTranslationSourceException();
    }
}