<?php


namespace Tolgee\Core\Loaders;


use PHPUnit\Exception;
use Tolgee\Core\Exceptions\FileReadException;
use Tolgee\Core\Exceptions\TranslationFileReadException;
use Tolgee\Core\Helpers\FileReader;
use Tolgee\Core\TolgeeConfig;

class LocalFileTranslationsLoader implements TranslationsLoader
{
    /**
     * @var TolgeeConfig
     */
    private $config;
    /**
     * @var FileReader
     */
    private $nativeWrapper;

    public function __construct(TolgeeConfig $config, FileReader $fileReader = null)
    {
        $this->config = $config;
        $this->nativeWrapper = $fileReader ?: new FileReader();
    }

    /**
     * @param string $lang
     * @return array
     * @throws TranslationFileReadException
     */
    function getTranslations(string $lang): array
    {
        $dir = $this->config->localFilesAbsolutePath;
        try {
            $fileName = $dir . "/" . $lang . ".json";
            $fileContent = $this->nativeWrapper->readFile($fileName);

        } catch (FileReadException $e) {
            throw new TranslationFileReadException($e);
        }
        return json_decode($fileContent, true);
    }

}