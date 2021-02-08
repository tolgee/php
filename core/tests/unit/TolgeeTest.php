<?php

use PHPUnit\Framework\TestCase;
use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Services\TextService;
use Tolgee\Core\Tolgee;
use Tolgee\Core\TolgeeConfig;
use function PHPUnit\Framework\assertEquals;

class TolgeeTest extends TestCase
{
    private $tolgee;
    private $textService;
    private $config;

    public function setUp(): void
    {
        $this->textService = $this->createMock(TextService::class);
        $this->config = $this->createMock(TolgeeConfig::class);
        $this->tolgee = new Tolgee($this->config, $this->textService);
    }

    public function testTranslateWrap()
    {
        $this->config->mode = Modes::DEVELOPMENT;
        $this->textService->method("wrap")->with("hello", [])->willReturn("wrapped");
        assertEquals("wrapped", $this->tolgee->translate("hello", []));
    }

    public function testTranslateNoWrap()
    {
        $this->textService->method("translate")->with("hello", [], "en")->willReturn("translated");
        assertEquals("translated", $this->tolgee->translate("hello", [], true));
    }

    public function testUsesCurrentLang()
    {
        $this->tolgee->setCurrentLang("dummyLang");
        $this->textService->method("translate")->with("hello", [], "dummyLang")
            ->willReturn("translated");
        assertEquals("translated", $this->tolgee->translate("hello", [], true));
    }

}