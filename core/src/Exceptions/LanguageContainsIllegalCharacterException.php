<?php


namespace Tolgee\Core\Exceptions;


use PHPUnit\Framework\Exception;

class LanguageContainsIllegalCharacterException extends Exception
{
    public function __construct($lang)
    {
        parent::__construct("Lang: '$lang' contains illegal character '.', '\\' or '/'");
    }
}