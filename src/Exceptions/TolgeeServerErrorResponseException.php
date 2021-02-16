<?php


namespace Tolgee\Core\Exceptions;


use Exception;
use Psr\Http\Message\ResponseInterface;

class TolgeeServerErrorResponseException extends Exception
{
    public function __construct(ResponseInterface $response)
    {
        $body = $response->getBody();
        $contentMessagePart = "";

        if ($body) {
            $contentMessagePart = $body->getContents() ? "Response content is: {$body->getContents()}" : "";
        }

        parent::__construct("Tolgee server responded with error status code {$response->getStatusCode()}.\n" .
            "$contentMessagePart");
    }
}