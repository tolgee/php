<?php

namespace Tolgee\Core\Helpers;

class TextHelper
{
    /**
     * @param $string
     * @param $delimiter
     * @return array
     */
    public static function splitOnNonEscapedDelimiter($string, $delimiter): array
    {
        $result = [];
        $actual = "";
        $split = str_split($string);
        for ($i = 0; $i < count($split); $i++) {
            $char = $split[$i];
            if ($char === $delimiter && !self::isCharEscaped($i, $split)) {
                array_push($result, self::removeEscapes($actual));
                $actual = "";
                continue;
            }
            $actual .= $split[$i];
        }
        array_push($result, self::removeEscapes($actual));
        return $result;
    }

    /**
     * @param $position int
     * @param array $charArray
     * @return bool
     */
    public static function isCharEscaped($position, array $charArray): bool
    {
        $escapeCharsCount = 0;
        while ($position > -1 && $charArray[$position - 1] === "\\") {
            $escapeCharsCount++;
            $position--;
        }
        return $escapeCharsCount % 2 == 1;
    }

    /**
     * @param $string string
     * @return string
     */
    public static function removeEscapes($string): string
    {
        return preg_replace_callback('/\\\\?\\\\?/', function ($match) {
            if ($match[0] === "\\\\") {
                return "\\";
            }
            return "";
        }, $string);
    }
}