<?php


namespace Tolgee\Core\Exceptions;


use Exception;

class TranslationFileReadException extends Exception
{
    public function __construct(FileReadException $exception)
    {
        parent::__construct($exception->getMessage() . "\n" .
            "Please check your localFilesAbsolutePath property of Tolgee config.");
    }
}