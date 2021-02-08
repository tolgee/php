<?php


namespace Tolgee\Core\Services;

use GuzzleHttp\Exception\GuzzleException;
use Tolgee\Core\Helpers\TextHelper;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;
use Tolgee\Core\TolgeeConfig;


class TranslationService
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

    public function getTranslation($key, $lang): string
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
     * @return string[]|array[]
     * @throws GuzzleException
     */
    private function getTranslations(string $lang): array
    {
        if (!array_key_exists($lang, $this->translationsCache)) {
            $this->translationsCache[$lang] = $this->translationsLoaderFactory->getLoader()->getTranslations($lang);
        }
        return $this->translationsCache[$lang];
    }

    /**
     * @param $key string
     * @param $lang string
     * @return string|null
     * @throws GuzzleException
     */
    private function getFromCache(string $key, string $lang)
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