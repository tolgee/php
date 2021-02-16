<?php


namespace Tolgee\Core\Exceptions;


use Exception;

class UnknownTranslationSourceException extends Exception
{
    public function __construct()
    {
        parent::__construct("Unknown Translation type!");
    }
}