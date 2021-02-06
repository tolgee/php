<?php


namespace Tolgee\Core\Loaders;


use GuzzleHttp\Client;
use Tolgee\Core\TolgeeConfig;

class ApiTranslationsLoader implements TranslationsLoader
{
    /**
     * @var TolgeeConfig
     */
    private $config;

    public function __construct(TolgeeConfig $config)
    {
        $this->config = $config;
    }

    function getTranslations($lang)
    {
        $apiKey = $this->config->apiKey;
        $response = $this->getClient()->get("/uaa/en?ak=$apiKey");
        $responseBodyContents = $response->getBody()->getContents();
        return json_decode($responseBodyContents, true);
    }

    /**
     * @return Client
     */
    private function getClient()
    {
        return new Client([
            'base_uri' => $this->config->apiUrl,
            'timeout' => 2.0,
        ]);
    }
}