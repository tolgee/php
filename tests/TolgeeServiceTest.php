<?php

use PHPUnit\Framework\TestCase;
use Tolgee\Core\Loaders\TranslationsLoader;
use Tolgee\Core\Loaders\TranslationsLoaderFactory;
use Tolgee\Core\TolgeeConfig;
use Tolgee\Core\TolgeeService;

class TolgeeServiceTest extends TestCase
{
    public function testGetTranslation()
    {
        /** @noinspection PhpParamsInspection */
        $config = $this->createMock(TolgeeConfig::class);
        /** @noinspection PhpParamsInspection */
        $loaderFactory = $this->createMock(TranslationsLoaderFactory::class);
        /** @noinspection PhpParamsInspection */
        $loader = $this->createMock(TranslationsLoader::class);
        $loaderFactory->method('getLoader')->willReturn($loader);
        $loader->method("getTranslations")->willReturn([
            "hello" => [
                "sub" => [
                    "sub2" => "This is it!"
                ]
            ]
        ]);
        $service = new TolgeeService($config, $loaderFactory);
        $result = $service->getTranslation("hello.sub.sub2", "en");
        $this->assertEquals("This is it!", $result);
    }
}