<?php

namespace RockSolidSoftware\BookRental\Helper;

final class Inflector
{

    /** @var string */
    protected static $encryptionKey = 'redSkyAtMorning';

    private function __clone() {}
    private function __construct() {}
    private function __wakeup() {}

    /**
     * Create slug for given string: remove all non-alphanumeric signs, change spaces into given char
     *
     * @param string $string
     * @param string $replaceWith
     * @return string
     */
    public static function createSlug(string $string, string $replaceWith = '-'): string
    {
        if (empty($string)) {
            return '';
        }

        $slug = strtr($string, [
            'ą' => 'a',
            'ę' => 'e',
            'ś' => 's',
            'ć' => 'c',
            'ł' => 'l',
            'ó' => 'o',
            'ź' => 'z',
            'ż' => 'z',
            'ń' => 'n',
            'Ą' => 'a',
            'Ę' => 'e',
            'Ś' => 's',
            'Ć' => 'c',
            'Ł' => 'l',
            'Ó' => 'o',
            'Ź' => 'z',
            'Ż' => 'z',
            'Ń' => 'n',
        ]);

        //$slug = preg_replace('/[^[:alnum:]]/u', $replaceWith, $slug);
        $slug = preg_replace('/[^A-Za-z0-9]/', $replaceWith, $slug);
        $slug = str_replace(['---', '--'], $replaceWith, $slug);

        if (in_array($slug[strlen($slug) - 1], ['-', '.', ',', ' ', $replaceWith])) {
            $slug = substr($slug, 0, strlen($slug) - 1);
        }

        return strtolower($slug);
    }

    /**
     * Generated (pseudo)random string
     *
     * @param int  $length
     * @param bool $onlyLetters
     * @return string
     */
    public static function randomString(int $length = 8, bool $onlyLetters = false): string
    {
        $alphaNumeric = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz' . ($onlyLetters ? '' : '01234567890123456789');
        return substr(str_shuffle($alphaNumeric), 0, $length);
    }

    /**
     * Generated (pseudo)random number
     *
     * @param int $length
     * @return string
     */
    public static function randomNumber(int $length = 8): string
    {
        $digits = '012345678901234567890123456789012345678901234567890123456789';
        return substr(str_shuffle($digits), 0, $length);
    }

    /**
     * Create normalized name, especially for files
     *
     * $mode = 1 - add random prefix (before) fileName, 2 - add random postfix (after) fileName
     *
     * @param string $name
     * @param int    $maxLength
     * @param int    $mode
     * @param int    $randomLength
     * @param string $separator
     * @return string
     */
    public static function normalizeFileName(
        string $name, int $maxLength = 20, int $mode = 2, int $randomLength = 3, string $separator = '_'): string
    {
        $slug = self::createSlug($name);

        if (strlen($slug) > $maxLength) {
            $tmp = substr($slug, 0, 20);
        } else {
            $tmp = $slug;
        }

        $random = self::randomString($randomLength);

        if ($mode == 1) {
            $fileName = $random . $separator . $tmp;
        } else if ($mode == 2) {
            $fileName = $tmp . $separator . $random;
        }

        return $fileName ?? '';
    }

    /**
     * Change given string to Camel Case notation
     *
     * @param string $string
     * @param bool   $capitalizeFirstCharacter
     * @return string
     */
    public static function toCamelCase(string $string, bool $capitalizeFirstCharacter = false): string
    {
        if (empty($string)) {
            return $string;
        }

        $str = str_replace(' ', '', ucwords(str_replace(['_', '-'], ' ', $string)));

        if (!$capitalizeFirstCharacter) {
            $str[0] = strtolower($str[0]);
        }

        return $str;
    }

    /**
     * Change given string to underscore notaction
     *
     * @param string $input
     * @param bool   $capitalizeFirstCharacter
     * @return string
     */
    public static function to_underscore(string $input, bool $capitalizeFirstCharacter = false): string
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];

        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }

        $result = implode('_', $ret);

        if ($capitalizeFirstCharacter) {
            return ucfirst($result);
        }

        return $result;
    }

    /**
     * Cut the text to given length and do not break words
     *
     * @param string $text
     * @param int    $length
     * @param string $postfix
     * @return string
     */
    public static function cutText(string $text, int $length, string $postfix = '...'): string
    {
        if (strlen($text) <= $length) {
            return $text;
        }

        $position = strpos($text, ' ', $length);

        if ($position === false) {
            return $text;
        }

        $cutted = substr($text,0,$position);

        if (strlen($cutted) != strlen($text) && !empty($postfix)) {
            return $cutted . $postfix;
        }

        return $cutted;
    }

    /**
     * Split given string by new line character into array
     *
     * @param string $content
     * @return array
     */
    public static function explodeByNewLine(string $content): array
    {
        return preg_split('/\r\n|\r|\n/', $content);
    }

    /**
     * Remove last char if it equals to the given value
     *
     * @param string $string
     * @param string $char
     * @return string
     */
    public static function trimLastChar(string $string, string $char): string
    {
        if (substr($string, -1, 1) == $char) {
            $string = substr($string, 0, -1);
        }

        return $string;
    }

    /**
     * Encrypt given string using encryption key
     *
     * @param string $plainText
     * @return string|null
     */
    public static function encrypt(string $plainText): ?string
    {
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = openssl_random_pseudo_bytes($ivlen);
        $cipherTextRaw = openssl_encrypt($plainText, $cipher, self::$encryptionKey, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $cipherTextRaw, self::$encryptionKey, true);

        return base64_encode($iv.$hmac.$cipherTextRaw);
    }

    /**
     * Decrypt encrypted string using encryption key
     *
     * @param string $encrypted
     * @return string|null
     */
    public static function decrypt(string $encrypted): ?string
    {
        $c = base64_decode($encrypted);
        $ivlen = openssl_cipher_iv_length($cipher = 'AES-128-CBC');
        $iv = substr($c, 0, $ivlen);
        $hmac = substr($c, $ivlen, $sha2len = 32);
        $cipherTextRaw = substr($c, $ivlen + $sha2len);
        $originalPlainText = openssl_decrypt($cipherTextRaw, $cipher, self::$encryptionKey, $options = OPENSSL_RAW_DATA, $iv);
        $calcmac = hash_hmac('sha256', $cipherTextRaw, self::$encryptionKey, true);

        if (hash_equals($hmac, $calcmac)) {
            return $originalPlainText;
        }

        return null;
    }

}
