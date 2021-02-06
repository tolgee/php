<?php


namespace Tolgee\Core\Loaders;


use Tolgee\Core\TolgeeConfig;

class LocalFileTranslationsLoader implements TranslationsLoader
{
    /**
     * @var TolgeeConfig
     */
    private $config;

    public function __construct(TolgeeConfig $config)
    {
        $this->config = $config;
    }

    function getTranslations($lang)
    {
        // TODO: Implement getTranslations() method.
    }
}