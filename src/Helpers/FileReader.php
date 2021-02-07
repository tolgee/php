<?php


namespace Tolgee\Core\Helpers;


use Tolgee\Core\Exceptions\FileReadException;

class FileReader
{
    public function readFile($filename): string
    {
        $result = "";
        $file = @fopen($filename, 'r');
        if ($file === false) {
            throw new FileReadException(error_get_last());
        }
        while ($line = fgets($file)) {
            $result .= $line;
        }
        fclose($file);
        return $result;
    }
}