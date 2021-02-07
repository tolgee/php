<?php


namespace Tolgee\Core\Exceptions;


use PHPUnit\Framework\Exception;
use Throwable;

class FileReadException extends Exception
{
    public function __construct(array $error)
    {
        parent::__construct($error["message"]);
    }
}