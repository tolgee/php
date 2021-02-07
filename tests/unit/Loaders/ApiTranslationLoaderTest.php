<?php

namespace Tolgee\Core\Loaders;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Tolgee\Core\Exceptions\TolgeeServerErrorResponseException;
use Tolgee\Core\Exceptions\TolgeeUnauthorizedException;
use Tolgee\Core\TolgeeConfig;

class ApiTranslationLoaderTest extends TestCase
{

    private $loader;

    /**
     * @var MockObject|ResponseInterface
     */
    private $response;

    function setUp(): void
    {
        $config = $this->createMock(TolgeeConfig::class);
        $config->apiUrl = "https://dummyurl.dummydomain";
        $config->apiKey = "dummyKey";
        $client = $this->createMock(Client::class);
        $this->loader = new ApiTranslationsLoader($config, $client);
        $this->response = $this->createMock(ResponseInterface::class);
        $apiKey = $config->apiKey;
        $client->method("request")
            ->with("GET", $config->apiUrl . "/uaa/en?ak=$apiKey")
            ->willReturn($this->response);
    }

    /**
     * @throws GuzzleException
     */
    function testGetTranslations()
    {
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock->method("getContents")->willReturn('{"en":{"hello_world":"Hello world!"}}');
        $this->response->method("getBody")->willReturn($bodyMock);
        $this->assertEquals(["hello_world" => "Hello world!"], $this->loader->getTranslations("en"));
    }

    /**
     * @throws GuzzleException
     */
    function testGetTranslationsLangNotExists()
    {
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock->method("getContents")->willReturn('{}');
        $this->response->method("getBody")->willReturn($bodyMock);
        $this->assertEquals([], $this->loader->getTranslations("en"));
    }

    /**
     * @throws GuzzleException
     */
    function testUnauthorized()
    {
        $this->response->method("getStatusCode")->willReturn(403);
        $this->expectException(TolgeeUnauthorizedException::class);
        $this->loader->getTranslations("en");
    }

    /**
     * @throws GuzzleException
     */
    function testServerErrorException()
    {
        $this->response->method("getStatusCode")->willReturn(400);
        $this->expectException(TolgeeServerErrorResponseException::class);
        $this->loader->getTranslations("en");
    }

    /**
     * @throws GuzzleException
     */
    function testServerErrorContentException()
    {
        $this->response->method("getStatusCode")->willReturn(400);
        $bodyMock = $this->createMock(StreamInterface::class);
        $bodyMock->method("getContents")->willReturn('Some kind of error!');
        $this->response->method("getBody")->willReturn($bodyMock);
        $this->expectException(TolgeeServerErrorResponseException::class);
        try {
            $this->loader->getTranslations("en");
        } catch (TolgeeServerErrorResponseException $e) {
            self::assertEquals("Tolgee server responded with error status code 400.\n" .
                "Response content is: Some kind of error!", $e->getMessage());
            throw $e;
        }
    }
}
