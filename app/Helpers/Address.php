<?php

namespace App\Helpers;

class Address
{
    /**
     * @param $address1
     * @param $address2
     * @param $city
     * @param $state
     * @param $zip
     * @return string
     */
    public static function getFullAddress($address1, $address2, $city, $state, $zip)
    {
        $address = ucwords(
            trim(strtolower($address1) . ' ' . strtolower($address2))
        );
        $city = ucwords(strtolower($city));
        $state = strtoupper($state);

        return sprintf('%s, %s, %s %s', $address, $city, $state, $zip);
    }
}