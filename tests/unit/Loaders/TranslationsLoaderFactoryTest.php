<?php

namespace Tolgee\Core\Loaders;

use PHPUnit\Framework\TestCase;
use Tolgee\Core\Enums\Modes;
use Tolgee\Core\Enums\TranslationSources;
use Tolgee\Core\Exceptions\UnknownTranslationSourceException;
use Tolgee\Core\TolgeeConfig;

class TranslationsLoaderFactoryTest extends TestCase
{

    private $factory;
    private $config;

    function setUp(): void
    {
        $this->config = new TolgeeConfig();
        $this->factory = new TranslationsLoaderFactory($this->config);
    }

    function testGetLoaderProduction()
    {
        self::assertInstanceOf(LocalFileTranslationsLoader::class, $this->factory->getLoader());
    }

    function testGetLoaderDevelopmentLocal()
    {
        $this->config->developmentTranslationsSource = TranslationSources::LOCAL_FILE;
        $this->config->mode = Modes::DEVELOPMENT;
        self::assertInstanceOf(LocalFileTranslationsLoader::class, $this->factory->getLoader());
    }

    function testGetLoaderDevelopmentApi()
    {
        $this->config->mode = Modes::DEVELOPMENT;
        self::assertInstanceOf(ApiTranslationsLoader::class, $this->factory->getLoader());
    }

    function testGetLoaderUnknown()
    {
        $this->expectException(UnknownTranslationSourceException::class);
        $this->config->developmentTranslationsSource = "dva pesa";
        $this->config->mode = Modes::DEVELOPMENT;
        $this->factory->getLoader();
    }
}
