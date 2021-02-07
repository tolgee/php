<?php

use PHPUnit\Framework\TestCase;
use Tolgee\Core\Loaders\TranslationsLoader;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;
use Tolgee\Core\TolgeeConfig;
use Tolgee\Core\Services\TranslationService;

class TranslationServiceTest extends TestCase
{
    private $service;

    public function setUp(): void
    {
        $config = $this->createMock(TolgeeConfig::class);
        $loaderFactory = $this->createMock(TranslationsLoaderFactory::class);
        $loader = $this->createMock(TranslationsLoader::class);
        $loaderFactory->method('getLoader')->willReturn($loader);
        $loader->method("getTranslations")->willReturn([
            "hello" => [
                "sub" => [
                    "sub2" => "This is it!"
                ]
            ],
            "root" => "I am translation in root",
            "escaped.delimiter" => "I am with escaped delimiter."
        ]);
        $this->service = new TranslationService($config, $loaderFactory);
    }

    public function testGetTranslationNested()
    {
        $result = $this->service->getTranslation("hello.sub.sub2", "en");
        $this->assertEquals("This is it!", $result);
    }


    public function testGetTranslationRoot()
    {
        $result = $this->service->getTranslation("root", "en");
        $this->assertEquals("I am translation in root", $result);
    }

    public function testGetTranslationEscapedDelimiter()
    {
        $result = $this->service->getTranslation("escaped\\.delimiter", "en");
        $this->assertEquals("I am with escaped delimiter.", $result);
    }
}