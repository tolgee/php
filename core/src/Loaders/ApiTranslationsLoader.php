<?php


namespace Tolgee\Core\Loaders;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\GuzzleException;
use Tolgee\Core\Exceptions\TolgeeServerErrorResponseException;
use Tolgee\Core\Exceptions\TolgeeUnauthorizedException;
use Tolgee\Core\TolgeeConfig;

class ApiTranslationsLoader implements TranslationsLoader
{
    /**
     * @var TolgeeConfig
     */
    private $config;

    /**
     * @var Client|null
     */
    private $client;


    public function __construct(TolgeeConfig $config, Client $client = null)
    {
        $this->config = $config;
        $this->client = $client ?: new Client();
    }

    /**
     * @param string $lang
     * @return array
     * @throws GuzzleException
     */
    function getTranslations(string $lang): array
    {
        $apiKey = $this->config->apiKey;
        $url = $this->config->apiUrl . "/uaa/$lang?ak=$apiKey";

        try {
            $response = $this->client->request("GET", $url,
                ['headers' => [
                    'Accept' => 'application/json',
                ]]
            );

        } catch (ClientException $e) {
            if ($e->getResponse()->getStatusCode() === 403) {
                throw new TolgeeUnauthorizedException();
            }

            if ($e->getResponse()->getStatusCode() >= 400) {
                throw new TolgeeServerErrorResponseException($e->getResponse());
            }
        }

        $translations = json_decode($response->getBody()->getContents(), true);
        if ($translations[$lang] === null) {
            return [];
        }

        return $translations[$lang];
    }
}