<?php

namespace Modules\Admin\Helpers;


/**
 * Class StringHelper
 * @package Modules\Admin\Helpers
 */
class StringHelper
{
    /**
     * @param $content
     * @return mixed|string
     */
    public static function normalizeEmailMessage($content)
    {
        $content = nl2br($content);
        $content = preg_replace('/<meta\s.*?\/>/is', '', $content);
        $content = preg_replace('/[^(\x20-\x7F)]*/', '', $content);
        $content = preg_replace('/[^[:print:]]/', '', $content);

        return $content;
    }

    /**
     * @param $str
     * @return bool|string
     */
    public static function sanitizeStr($str)
    {
        // Converting to standard RFC 4648 base64-encoding
        // see http://en.wikipedia.org/wiki/Base64#Implementations_and_history
        $sanitized = strtr($str, '-_', '+/');
        return base64_decode($sanitized);
    }

    /**
     * @param $value
     * @param $format
     * @return string
     */
    public static function formatValue($value, $format) {
        switch($format) {
            case 'number':
                return number_format((integer) $value);

            case 'currency':
                return sprintf('%.2f', floatval(str_replace(',', '', $value)));
        }

        return $value;
    }

    /**
     * @param $str
     * @param string $keywords
     * @return mixed
     */
    public static function highlightWords($str, $keywords = '') {
        $keywords = preg_replace('/\s\s+/', ' ', strip_tags(trim($keywords))); // filter

        /* Apply Style */

        $var = '';

        foreach(explode(' ', $keywords) as $keyword) {
            $replacement = "<mark>".$keyword."</mark>";
            $var .= $replacement." ";

            $str = str_ireplace($keyword, $replacement, $str);
        }


        return $str;
    }

    /**
     * @param $phone
     * @return mixed
     */
    public static function formatPhone($phone) {
        $phone = str_replace(array('(', ')', '-', ' ', '.'), '', $phone);
        return preg_replace("/([0-9]{3})([0-9]{3})([0-9]{4})/","$1-$2-$3", $phone);
    }

    public static function creditCardCompany($ccNum)
    {
        /*
            * mastercard: Must have a prefix of 51 to 55, and must be 16 digits in length.
            * Visa: Must have a prefix of 4, and must be either 13 or 16 digits in length.
            * American Express: Must have a prefix of 34 or 37, and must be 15 digits in length.
            * Diners Club: Must have a prefix of 300 to 305, 36, or 38, and must be 14 digits in length.
            * Discover: Must have a prefix of 6011, and must be 16 digits in length.
            * JCB: Must have a prefix of 3, 1800, or 2131, and must be either 15 or 16 digits in length.
        */

        if (preg_match("/^5[1-5][0-9]{14}$/", $ccNum))
            return "Mastercard";

        if (preg_match("/^4[0-9]{12}([0-9]{3})?$/", $ccNum))
            return "Visa";

        if (preg_match("/^3[47][0-9]{13}$/", $ccNum))
            return "American Express";

        if (preg_match("/^3(0[0-5]|[68][0-9])[0-9]{11}$/", $ccNum))
            return "Diners Club";

        if (preg_match("/^6011[0-9]{12}$/", $ccNum))
            return "Discover";

        if (preg_match("/^(3[0-9]{4}|2131|1800)[0-9]{11}$/", $ccNum))
            return "JCB";
        return 'test';
    }

    /**
     * @param $number
     * @return string
     */
    public static function maskCreditCard($number)
    {
        return 'XXXX-' . substr($number, -4, 4);
    }

    /**
     * @param $cc
     * @return bool|string
     */
    public static function formatCreditCard($cc)
    {
        // Clean out extra data that might be in the cc
        $cc = str_replace(['-', ' '], '', $cc);
        // Get the CC Length
        $cc_length = strlen($cc);
        // Initialize the new credit card to contian the last four digits
        $newCreditCard = substr($cc, -4);
        // Walk backwards through the credit card number and add a dash after every fourth digit
        for($i = $cc_length -5; $i >= 0; $i--) {
            // If on the fourth character add a dash
            if((($i+1) - $cc_length) % 4 == 0){
                $newCreditCard = '-' . $newCreditCard;
            }
            // Add the current character to the new credit card
            $newCreditCard = $cc[$i] . $newCreditCard;
        }
        // Return the formatted credit card number
        return $newCreditCard;
    }

    public static function encode($text)
    {
        return htmlspecialchars($text,ENT_QUOTES, 'UTF-8');
    }

    public static function getNumbersOnly($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }

    public static function getCustomShortHash($length = 5)
    {
        return substr(sha1(microtime(true) . mt_rand(0, 999)), 0, $length);
    }
}