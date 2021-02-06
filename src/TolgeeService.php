<?php


namespace Tolgee\Core;

use Tolgee\Core\Helpers\TextHelper;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;


class TolgeeService
{
    /**
     * @var TolgeeConfig Tolgee configuration
     */
    private $config;

    /**
     * @var mixed[][]
     */
    private $translationsCache = [];
    /**
     * @var TranslationsLoaderFactory
     */
    private $translationsLoaderFactory;

    public function __construct(TolgeeConfig $config, TranslationsLoaderFactory $translationsLoaderFactory)
    {
        $this->config = $config;
        $this->translationsLoaderFactory = $translationsLoaderFactory;
    }

    function getTranslation($key, $lang)
    {
        $translatedText = $this->getFromCache($key, $lang) ?: $this->getFromCache($key, $this->config->fallbackLanguage);

        if ($translatedText) {
            return $translatedText;
        }

        $path = TextHelper::splitOnNonEscapedDelimiter($key, ".");
        return $path[count($path) - 1];
    }

    /**
     * @param $lang string
     * @return mixed[]
     */
    private function getTranslations($lang)
    {
        if ($this->translationsCache[$lang] === null) {
            $this->translationsCache[$lang] = $this->translationsLoaderFactory->getLoader()->getTranslations($lang);
        }
        return $this->translationsCache[$lang];
    }

    /**
     * @param $key string
     * @param $lang string
     * @return string|null
     */
    private function getFromCache($key, $lang)
    {
        $path = TextHelper::splitOnNonEscapedDelimiter($key, ".");
        $root = $this->getTranslations($lang);

        if ($root === null) {
            return null;
        }

        foreach ($path as $item) {
            if ($root[$item] === null) {
                return null;
            }
            $root = $root[$item];
        }
        return $root;
    }
}