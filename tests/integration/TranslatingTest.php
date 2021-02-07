<?php


namespace Tolgee\Core;


use GuzzleHttp\Client;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Enums\TranslationSources;
use Tolgee\Core\Exceptions\TranslationFileReadException;
use Tolgee\Core\Loaders\ApiTranslationsLoader;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;
use Tolgee\Core\Services\TextService;
use Tolgee\Core\Services\TranslationService;

class TranslatingTest extends TestCase
{
    const TRANSLATIONS_FILES_DIR = "/test_i18n";


    function testTranslatingLocal()
    {
        $config = new TolgeeConfig();
        $config->localFilesAbsolutePath = __DIR__ . self::TRANSLATIONS_FILES_DIR;
        $tolgee = new Tolgee($config);
        self::assertEquals("Hello world!", $tolgee->translate("hello_world", [], true));
    }

    function testTranslatingLocalMissingFile()
    {
        $config = new TolgeeConfig();
        $config->developmentTranslationsSource = TranslationSources::LOCAL_FILE;
        $config->mode = Modes::DEVELOPMENT;
        $config->localFilesAbsolutePath = __DIR__ . self::TRANSLATIONS_FILES_DIR;
        $tolgee = new Tolgee($config);
        $this->expectException(TranslationFileReadException::class);
        $tolgee->translate("hello_world", [], true, "de");
    }

    function testTranslatingApi()
    {
        $config = new TolgeeConfig();
        $config->developmentTranslationsSource = TranslationSources::LOCAL_FILE;
        $config->mode = Modes::DEVELOPMENT;
        $config->localFilesAbsolutePath = __DIR__ . self::TRANSLATIONS_FILES_DIR;
        $response = $this->createMock(ResponseInterface::class);
        $apiKey = $config->apiKey;
        $client = $this->createMock(Client::class);
        $client->method("request")
            ->with("GET", $config->apiUrl . "/uaa/en?ak=$apiKey")
            ->willReturn($response);
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock->method("getContents")->willReturn('{"en":{"hello_world":"Hello world!"}}');
        $response->method("getBody")->willReturn($bodyMock);
        $apiTranslationLoader = new ApiTranslationsLoader($config, $client);
        $translationsLoaderFactory = new TranslationsLoaderFactory($config, $apiTranslationLoader);
        $translationService = new TranslationService($config, $translationsLoaderFactory);
        $tolgee = new Tolgee($config, new TextService($translationService, $config));
        self::assertEquals("Hello world!", $tolgee->translate("hello_world", [], true));
    }

    function testWrapping()
    {
        $config = new TolgeeConfig();
        $config->mode = Modes::DEVELOPMENT;
        $config->inputPrefix = "|translationStart|";
        $config->inputSuffix = "|translationEnd|";
        $tolgee = new Tolgee($config);
        self::assertEquals("|translationStart|hello_world|translationEnd|", $tolgee->translate("hello_world", []));
    }
}