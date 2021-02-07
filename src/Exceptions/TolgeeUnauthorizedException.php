<?php


namespace Tolgee\Core\Exceptions;


use PHPUnit\Framework\Exception;
use Throwable;

class TolgeeUnauthorizedException extends Exception
{

    public function __construct()
    {
        parent::__construct("Tolgee server returned 403 - Unauthorized status. " .
            "Check you apiKey and apiUrl configuration properties.");
    }
}