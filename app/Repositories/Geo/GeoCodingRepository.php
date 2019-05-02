<?php

namespace App\Repositories\Geo;

use GuzzleHttp\Client;
use App\Models\Geo\AddressGeoCode;

class GeoCodingRepository
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client;
    }

    /**
     *
     * @param string $address
     * @return
     */
    public function getGeoCode($address, $forceRefresh=false)
    {
        $originalAddress = $address;
        $address = str_replace(["#", " "], ["%23", "%20"], $address);
        $response = null;

        if(!$forceRefresh) {
          if($exists = AddressGeoCode::cachedAddress(sha1($originalAddress))) {
            return $exists + ['success' => true];
          }
        }

        $bingGeoCodeApi = $this->bingGeoCodeApi($address);
           
        if ($bingGeoCodeApi['success']) {
            $response = $bingGeoCodeApi;
        } else {
            $googleGeoCode = $this->googleGeoCodeApi($address);

            if ($googleGeoCode['success']) {
                $response = $googleGeoCode;
            }
        }

        if ($response) {
            // Save Geo Coded Address
            $this->save($originalAddress, $response);
            return $response;
        }

        return null;
    }

    /**
     *
     * @param string $address
     * @return
     */
    public function googleGeoCodeApi($address)
    {
        $googleGeoCode = $this->client->request('GET', 'https://maps.googleapis.com/maps/api/geocode/json?address='.urlencode($address).'&sensor=false&&key=' . $this->getGoogleKey());
        $geoOutput = json_decode((string) $googleGeoCode->getBody());

        // if google response return OK status
        if ($geoOutput->status == 'OK') {

            // See if we have a result
            if (count($geoOutput->results) && isset($geoOutput->results[0]) && isset($geoOutput->results[0]->geometry) && isset($geoOutput->results[0]->geometry->location)) {

                // Update appraiser lat long
                $apprLatPos = $geoOutput->results[0]->geometry->location->lat;
                $apprLongPos = $geoOutput->results[0]->geometry->location->lng;
                $addressComponent = $geoOutput->results[0]->address_components;
                
                $response = [
                  'success' => true,
                  'address' => trim($this->getGoogleAddressComponentByType($addressComponent, 'street_number') . ' ' . $this->getGoogleAddressComponentByType($addressComponent, 'route')),
                  'city' => $this->getGoogleAddressComponentByType($addressComponent, 'locality'),
                  'state' => $this->getGoogleAddressComponentByType($addressComponent, 'administrative_area_level_1'),
                  'zip' => $this->getGoogleAddressComponentByType($addressComponent, 'postal_code'),
                  'country' => $this->getGoogleAddressComponentByType($addressComponent, 'country', 'long_name'),
                  'lat' => $apprLatPos,
                  'long' => $apprLongPos,
                  'service' => 'google',
                ];

                $response['full'] = trim($response['address'] . ', ' . $response['city'] . ', ' . $response['state'] . ' ' . $response['zip']);
            }
        } else {
            if ($geoOutput->status == 'ZERO_RESULTS') {
                $response = ['success' => false, 'message' => $geoOutput->status.'</br>'. 'Google Api not found address'];
            } else {
                $response = ['success' => false, 'message' => $geoOutput->status.'</br>'.$geoOutput->error_message];
            }
        }

        return $response;
    }

    /**
     *
     * @param string $address
     * @return
     */
    public function bingGeoCodeApi($address)
    {
        $bingGeoCode = $this->client->request('GET', 'https://dev.virtualearth.net/REST/v1/Locations?query='.$address.'&key='. $this->getBingKey());
        $resource = json_decode((string) $bingGeoCode->getBody())->resourceSets[0];
    
        if ($resource->estimatedTotal > 0) {
            $lat = $resource->resources[0]->point->coordinates[0];
            $long = $resource->resources[0]->point->coordinates[1];
          
            $response = [
              'success' => true,
              'address' => data_get($resource->resources[0], 'address.addressLine', ''),
              'city' => data_get($resource->resources[0], 'address.locality', ''),
              'state' => data_get($resource->resources[0], 'address.adminDistrict', ''),
              'zip' => data_get($resource->resources[0], 'address.postalCode', ''),
              'country' => data_get($resource->resources[0], 'address.countryRegion', ''),
              'full' => data_get($resource->resources[0], 'address.formattedAddress', ''),
              'lat' => $lat,
              'long' => $long,
              'service' => 'bing'
            ];
        } else {
            $response = ['success' => false, 'message' => 'Geo Address not found (Bing Map) '];
        }

        return $response;
    }

    protected function getGoogleKey()
    {
        return collect(config('services.google.maps.keys'))->filter(function($value) {
          return !empty($value);
        })->random();
    }

    protected function getBingKey()
    {
        return config('services.bing.key');
    }

    protected function save($originalAddress, $response)
    {
      $hash = sha1($originalAddress);
      $response['hash'] = $hash;

      AddressGeoCode::byHash($hash)->delete();

      return AddressGeoCode::create($response);
    }

    protected function getGoogleAddressComponentByType($addressComponent, $type, $useLong=false)
    {
        $row = collect($addressComponent)->filter(function ($item) use($type) {
          return in_array($type, $item->types);
        });

        if($row && ($i = $row->first())) {
          if($useLong) {
            return $i->long_name ?? $i->short_name;
          }
          return $i->short_name ?? $i->long_name;
        }

        return null;
    }
}
