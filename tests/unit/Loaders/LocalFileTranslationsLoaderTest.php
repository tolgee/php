<?php

namespace Tolgee\Core\Loaders;

use Cassandra\Exception\UnauthorizedException;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tolgee\Core\Exceptions\FileReadException;
use Tolgee\Core\Exceptions\TolgeeServerErrorResponseException;
use Tolgee\Core\Exceptions\TolgeeUnauthorizedException;
use Tolgee\Core\Exceptions\TranslationFileReadException;
use Tolgee\Core\Helpers\FileReader;
use Tolgee\Core\TolgeeConfig;

class LocalFileTranslationsLoaderTest extends TestCase
{
    private $loader;

    /**
     * @var MockObject|FileReader
     */
    private $fileReader;

    function setUp(): void
    {
        $config = $this->createMock(TolgeeConfig::class);
        $this->fileReader = $this->createMock(FileReader::class);
        $this->loader = new LocalFileTranslationsLoader($config, $this->fileReader);
    }

    /**
     * @throws TranslationFileReadException
     */
    function testGetTranslationsBasic()
    {
        $this->fileReader->method("readFile")
            ->willReturn('{"hello_world":"Hello world!"}');
        $this->assertEquals(["hello_world" => "Hello world!"], $this->loader->getTranslations("en"));
    }

    /**
     * @throws TranslationFileReadException
     */
    function testGetTranslationsFileNotExists()
    {
        $this->expectException(TranslationFileReadException::class);
        $this->fileReader->method("readFile")
            ->willThrowException(new FileReadException([
                    "message" => "fopen(/path/aa.json): failed to open stream: No such file or directory"]
            ));
        try {
            $this->loader->getTranslations("en");
        } catch (TranslationFileReadException $e) {
            $expected = "fopen(/path/aa.json): failed to open stream: No such file or directory\n" .
                "Please check your localFilesAbsolutePath property of Tolgee config.";
            self::assertEquals($expected, $e->getMessage());
            throw $e;
        }
    }
}
