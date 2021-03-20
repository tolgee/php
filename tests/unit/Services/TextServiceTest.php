<?php

use PHPUnit\Framework\TestCase;
use Tolgee\Core\Services\TextService;
use Tolgee\Core\Services\TranslationService;
use Tolgee\Core\TolgeeConfig;

class TextServiceTest extends TestCase
{
    private $service;
    private $translationService;

    public function setUp(): void
    {
        $this->translationService = $this->createMock(TranslationService::class);
        $this->service = new TextService($this->translationService, new TolgeeConfig());
    }

    public function testSimpleTranslation()
    {
        $this->translationService->method("getTranslation")->willReturn("I am the translation");
        $result = $this->service->translate("hello", [], "en");
        $this->assertEquals("I am the translation", $result);
    }

    public function testTranslationWithSimpleParams()
    {
        $this->translationService->method("getTranslation")
            ->willReturn("Hello, {name} has {animalCount} {animalsKind}.");
        $params = ["name" => "Peter", "animalCount" => 10, "animalsKind" => "gorillas"];
        $result = $this->service->translate("hello", $params, "en");
        $this->assertEquals("Hello, Peter has 10 gorillas.", $result);
    }

    public function testTranslationWithParamsWithRegexChars()
    {
        $this->translationService->method("getTranslation")->willReturn("Hello, {name}.");
        $result = $this->service->translate("hello", ["name" => "Peter"], "en");
        $this->assertEquals("Hello, Peter.", $result);
    }

    public function testWrapBasic()
    {
        $wrapped = $this->service->wrap("key", ["param" => "value"]);
        $this->assertEquals("%-%tolgee:key:param:value%-%", $wrapped);
    }

    public function testWrapBasicEscaping()
    {
        $params = [":,param:," => ",:val:ue:,", "param2,:a" => ":val,:ue2"];
        $wrapped = $this->service->wrap(",key:aaa,aa:z:", $params);
        $expected = "%-%tolgee:\\,key\\:aaa\\,aa\\:z\\::\\:\\,param\\:\\,:\\,\\:val\\:ue\\:\\,"
            . ",param2\\,\\:a:\\:val\\,\\:ue2%-%";
        $this->assertEquals($expected, $wrapped);
    }
}