<?php


namespace Tolgee\Core\Services;


use MessageFormatter;
use Tolgee\Core\TolgeeConfig;

class TextService
{
    /**
     * @var TranslationService
     */
    private $translationService;
    /**
     * @var TolgeeConfig
     */
    private $config;

    public function __construct(TranslationService $translationService, TolgeeConfig $config)
    {
        $this->translationService = $translationService;
        $this->config = $config;
    }

    public function translate(string $key, array $params, string $lang): string
    {
        $translation = $this->translationService->getTranslation($key, $lang);
        return $this->format($translation, $lang, $params);
    }

    private function format(string $translation, $lang, array $params = []): string
    {
        return MessageFormatter::formatMessage($lang, $translation, $params);
    }

    public function wrap(string $key, array $params = []): string
    {
        $result = $this->config->inputPrefix . $this->escapeParam($key);
        if (!empty($params)) {
            $paramStrings = [];
            foreach ($params as $name => $value) {
                array_push($paramStrings, $this->escapeParam($name) . ":" . $this->escapeParam($value));
            }
            $result .= ":" . join(",", $paramStrings);
        }
        return $result . $this->config->inputSuffix;
    }

    /**
     * @param string $string
     * @return string
     */
    private function escapeParam(string $string): string
    {
        return preg_replace("/([,:\\\\])/", "\\\\$1", $string);
    }
}