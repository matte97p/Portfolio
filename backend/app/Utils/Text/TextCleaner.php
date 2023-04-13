<?php

namespace App\Utils\Text;

class TextCleaner
{
    /**
     * @param string $text
     *
     * @return string
     */
    static function removeEmojis(string $text)
    {
        if(!empty($text))
        {
            $text = preg_replace('/[\x{1F600}-\x{1F6FF}]/u', '', $text);
        }
    }

    /**
     * @param string $text
     * @param int $maxLenght
     *
     * @return string
     */
    static function trimContent(string $text, int $maxLenght = 1000)
    {
        $text = self::removeEmojis($text);

        return mb_strimwidth($text, 0, $maxLenght, '...');
    }

    /**
     * @param string ...$strings
     *
     * @return string
     */
    static function concatenateStrings(...$strings)
    {
        foreach($strings as $i => $string)
        {
            if(empty(trim($string)))
            {
                unset($strings[$i]);
            }
        }

        return implode(' ', $strings);
    }
}
