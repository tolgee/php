<?php

namespace Tolgee\Core;

use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;
use Tolgee\Core\Services\TextService;
use Tolgee\Core\Services\TranslationService;

class Tolgee
{

    /**
     * @var TolgeeConfig
     */
    private $config;

    /**
     * @var TranslationService
     */
    private $translationService;

    /**
     * @var TextService
     */
    private $textService;

    private $currentLang;

    /**
     * @return mixed
     */
    public function getCurrentLang(): string
    {
        return $this->currentLang;
    }

    /**
     * @param string $currentLang
     */
    public function setCurrentLang(string $currentLang): void
    {
        $this->currentLang = $currentLang;
    }

    public function __construct(TolgeeConfig $config = null, $textService = null)
    {
        $this->translationService = new TranslationService($config, new TranslationsLoaderFactory($config));
        $textService = $textService ?: new TextService($this->translationService, $config);
        $this->config = $config ?: new TolgeeConfig();
        $this->currentLang = $config->defaultLanguage;
        $this->textService = $textService;
    }

    function translate(string $key, array $params = [], bool $noWrap = false, $language = null): string
    {
        $language = $language ?: $this->currentLang;
        if ($noWrap || $this->config->mode === Modes::PRODUCTION) {
            return $this->textService->translate($key, $params, $language);
        }
        return $this->textService->wrap($key, $params);
    }
}