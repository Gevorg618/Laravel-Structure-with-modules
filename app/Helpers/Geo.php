<?php

use App\Repositories\Geo\GeoCodingRepository;

/**
 * Return a list of states ordered by state name
 * @return array
 */
function getStates($shortVersion = false)
{
    $regions = getStatesByRegion();
    $list = [];
    foreach ($regions as $region => $states) {
        foreach ($states as $key => $value) {
            $list[$key] = $shortVersion ? $key : $value;
        }
    }
    asort($list);
    return $list;
}

function getStateByAbbr($abbr)
{
    return getStates()[$abbr] ?? null;
}

function getTimeZoneList()
{
    $timeZones = DateTimeZone::listIdentifiers(DateTimeZone::PER_COUNTRY, 'US');
    $list = [];
    foreach ($timeZones as $timeZone) {
        $list[$timeZone] = $timeZone;
    }

    return $list;
}

function getAdjacentStates()
{
    return [
        'AK' => [],
        'AL' => ['FL', 'GA', 'MS', 'TN'],
        'AR' => ['LA', 'MO', 'MS', 'OK', 'TN', 'TX'],
        'AZ' => ['CA', 'CO', 'NM', 'NV', 'UT'],
        'CA' => ['NV', 'OR', 'AZ'],
        'CO' => ['KS', 'NE', 'NM', 'OK', 'UT', 'WY'],
        'CT' => ['MA', 'NY', 'RI'],
        'DC' => ['MD', 'VA'],
        'DE' => ['MD', 'NJ', 'PA'],
        'FL' => ['GA', 'AL'],
        'GA' => ['AL', 'SC', 'TN', 'FL'],
        'HI' => ['HI'],
        'IA' => ['IL', 'MN', 'MO', 'NE', 'SD', 'WI'],
        'ID' => ['MT', 'NV', 'OR', 'UT', 'WA', 'WY'],
        'IL' => ['IN', 'KY', 'MO', 'WI', 'IA', 'MI'],
        'IN' => ['KY', 'MI', 'OH', 'IL'],
        'KS' => ['MO', 'NE', 'OK', 'CO'],
        'KY' => ['MO', 'OH', 'TN', 'VA', 'WV', 'IN', 'IL'],
        'LA' => ['MS', 'TX', 'AR'],
        'MA' => ['NH', 'NY', 'RI', 'VT', 'CT'],
        'MD' => ['PA', 'VA', 'WV', 'DE'],
        'ME' => ['NH'],
        'MI' => ['OH', 'WI', 'IL', 'IN'],
        'MN' => ['ND', 'SD', 'WI', 'IA'],
        'MO' => ['NE', 'OK', 'TN', 'KS', 'AR', 'KY', 'IL'],
        'MS' => ['TN', 'AL', 'AR', 'LA'],
        'MT' => ['ND', 'SD', 'WY', 'ID'],
        'NC' => ['SC', 'TN', 'VA'],
        'ND' => ['SD', 'MN', 'MT'],
        'NE' => ['SD', 'WY', 'CO', 'KS', 'MO'],
        'NH' => ['VT', 'MA', 'ME'],
        'NJ' => ['NY', 'PA'],
        'NM' => ['OK', 'TX', 'UT', 'CO', 'AZ'],
        'NV' => ['OR', 'UT', 'ID', 'CA', 'AZ'],
        'NY' => ['PA', 'VT', 'MA', 'CT', 'NJ'],
        'OH' => ['PA', 'WV', 'KY', 'IN', 'MI'],
        'OK' => ['TX', 'AR', 'MO', 'KS', 'CO', 'NM'],
        'OR' => ['WA', 'CA', 'NV', 'ID'],
        'PA' => ['WV', 'NJ', 'MD', 'NY', 'OH'],
        'RI' => ['CT', 'MA'],
        'SC' => ['NC', 'GA'],
        'SD' => ['WY', 'MT', 'ND', 'MN', 'IA', 'NE'],
        'TN' => ['VA', 'KY', 'NC', 'TN', 'MO', 'IL', 'IN', 'OH', 'WV'],
        'TX' => ['LA', 'AR', 'OK', 'NM'],
        'UT' => ['WY', 'CO', 'NM', 'AZ', 'NV', 'ID'],
        'VA' => ['WV', 'MD', 'KY', 'TN', 'NC'],
        'WA' => ['ID', 'OR'],
        'WV' => ['PA', 'MD', 'VA', 'KY', 'OH'],
        'WI' => ['MI', 'IL', 'IA', 'MN'],
        'WY' => ['MT', 'SD', 'NE', 'CO', 'UT', 'ID'],
    ];
}


function getTimeZoneByState($state)
{
    return getStateTimeZones()[$state] ?? null;
}

function getStateTimeZones()
{
    return [
        'AK' => 'America/Anchorage',
        'AL' => 'America/Chicago',
        'AR' => 'America/Chicago',
        'AZ' => 'America/Phoenix',
        'CA' => 'America/Los_Angeles',
        'CO' => 'America/Denver',
        'CT' => 'America/New_York',
        'DC' => 'America/New_York',
        'DE' => 'America/New_York',
        'FL' => 'America/New_York',
        'GA' => 'America/New_York',
        'HI' => 'Pacific/Honolulu',
        'IA' => 'America/Chicago',
        'ID' => 'America/Boise',
        'IL' => 'America/Chicago',
        'IN' => 'America/Indiana/Indianapolis',
        'KS' => 'America/Chicago',
        'KY' => 'America/Kentucky/Louisville',
        'LA' => 'America/Chicago',
        'MA' => 'America/New_York',
        'MD' => 'America/New_York',
        'ME' => 'America/New_York',
        'MI' => 'America/Detroit',
        'MN' => 'America/Chicago',
        'MO' => 'America/Chicago',
        'MS' => 'America/Chicago',
        'MT' => 'America/Denver',
        'NC' => 'America/New_York',
        'ND' => 'America/North_Dakota/Center',
        'NE' => 'America/Chicago',
        'NH' => 'America/New_York',
        'NJ' => 'America/New_York',
        'NM' => 'America/Denver',
        'NV' => 'America/Los_Angeles',
        'NY' => 'America/New_York',
        'OH' => 'America/New_York',
        'OK' => 'America/Chicago',
        'OR' => 'America/Los_Angeles',
        'PA' => 'America/New_York',
        'PR' => 'America/New_York',
        'RI' => 'America/New_York',
        'SC' => 'America/New_York',
        'SD' => 'America/Chicago',
        'TN' => 'America/Chicago',
        'TX' => 'America/Chicago',
        'UT' => 'America/Denver',
        'VA' => 'America/New_York',
        'WA' => 'America/Los_Angeles',
        'VT' => 'America/New_York',
        'WV' => 'America/New_York',
        'WI' => 'America/Chicago',
        'WY' => 'America/Denver',
    ];
}

function getRegionsByStateAbbr($abbrState) {
  return getStateRegions()[$abbrState] ?? null;
}

/**
 * Return list of states grouped by region
 * @return array
 */
function getStateRegions()
{
    return [
        'CT' => 'Eastern',
        'DE' => 'Eastern',
        'DC' => 'Eastern',
        'FL' => 'Eastern',
        'GA' => 'Eastern',
        'IN' => 'Eastern',
        'KY' => 'Eastern',
        'ME' => 'Eastern',
        'MD' => 'Eastern',
        'MA' => 'Eastern',
        'MI' => 'Eastern',
        'NH' => 'Eastern',
        'NJ' => 'Eastern',
        'NY' => 'Eastern',
        'NC' => 'Eastern',
        'OH' => 'Eastern',
        'PA' => 'Eastern',
        'PR' => 'Eastern',
        'RI' => 'Eastern',
        'SC' => 'Eastern',
        'VI' => 'Eastern',
        'VT' => 'Eastern',
        'VA' => 'Eastern',
        'WV' => 'Eastern',

        'AL' => 'Central',
        'AR' => 'Central',
        'IL' => 'Central',
        'IA' => 'Central',
        'KS' => 'Central',
        'LA' => 'Central',
        'MN' => 'Central',
        'MS' => 'Central',
        'MO' => 'Central',
        'NE' => 'Central',
        'ND' => 'Central',
        'OK' => 'Central',
        'SD' => 'Central',
        'TN' => 'Central',
        'TX' => 'Central',
        'WI' => 'Central',

        'CO' => 'Mountain',
        'ID' => 'Mountain',
        'MT' => 'Mountain',
        'NM' => 'Mountain',
        'UT' => 'Mountain',
        'WY' => 'Mountain',

        'AZ' => 'Pacific',
        'CA' => 'Pacific',
        'NV' => 'Pacific',
        'OR' => 'Pacific',
        'WA' => 'Pacific',

        'AK' => 'Alaska',
        'HI' => 'Hawaii',
    ];
}

/**
 * Return list of states grouped by region
 * @return array
 */
function getStatesByRegion()
{
    return [
        'Eastern' => [
            'CT' => 'Connecticut',
            'DE' => 'Delaware',
            'DC' => 'District Of Columbia',
            'FL' => 'Florida',
            'GA' => 'Georgia',
            'IN' => 'Indiana',
            'KY' => 'Kentucky',
            'ME' => 'Maine',
            'MD' => 'Maryland',
            'MA' => 'Massachusetts',
            'MI' => 'Michigan',
            'NH' => 'New Hampshire',
            'NJ' => 'New Jersey',
            'NY' => 'New York',
            'NC' => 'North Carolina',
            'OH' => 'Ohio',
            'PA' => 'Pennsylvania',
            'PR' => 'Puerto Rico',
            'RI' => 'Rhode Island',
            'SC' => 'South Carolina',
            'VI' => 'Virgin Islands',
            'VT' => 'Vermont',
            'VA' => 'Virginia',
            'WV' => 'West Virginia',
        ],
        'Central' => [
            'AL' => 'Alabama',
            'AR' => 'Arkansas',
            'IL' => 'Illinois',
            'IA' => 'Iowa',
            'KS' => 'Kansas',
            'LA' => 'Louisiana',
            'MN' => 'Minnesota',
            'MS' => 'Mississippi',
            'MO' => 'Missouri',
            'NE' => 'Nebraska',
            'ND' => 'North Dakota',
            'OK' => 'Oklahoma',
            'SD' => 'South Dakota',
            'TN' => 'Tennessee',
            'TX' => 'Texas',
            'WI' => 'Wisconsin',
        ],
        'Mountain' => [
            'CO' => 'Colorado',
            'ID' => 'Idaho',
            'MT' => 'Montana',
            'NM' => 'New Mexico',
            'UT' => 'Utah',
            'WY' => 'Wyoming'
        ],
        'Pacific' => [
            'AZ' => 'Arizona',
            'CA' => 'California',
            'NV' => 'Nevada',
            'OR' => 'Oregon',
            'WA' => 'Washington',
        ],
        'Other' => [
            'AK' => 'Alaska',
            'HI' => 'Hawaii',
        ],
    ];
}

function getStateKeys()
{
    return implode(',', array_keys(getStates()));
}

function toDate($date, $format = 'Y-m-d H:m')
{
    return \Carbon\Carbon::createFromTimestamp($date)->format($format);
}

function getRegionByState($state)
{
    $states = getStateRegions();
    return $states[$state] ?? config('constants.not_available');
}

function getRegions()
{
    $regions = getStateRegions();
    $list = [];
    foreach ($regions as $state => $region) {
        $list[$region] = $region;
    }

    return $list;
}

function getStatesByRegions($regions)
{
    if (!$regions) {
        return false;
    }

    $states = array_filter(getStateRegions(), function ($value) use ($regions) {
        return in_array($value, $regions);
    });

    return array_keys($states);
}

function getStatesInRegion($match)
{
    $list = getStatesByRegion();

    foreach($list as $region => $states) {
        if($match == $region) {
            return $states;
        }
    }

    if($match == 'Hawaii') {
        return array('HI' => 'HI');
    } elseif($match == 'Alaska') {
        return array('AK' => 'AK');
    }
    return array();
}

function geoCode($address) 
{
    return (new GeoCodingRepository)->getGeoCode($address);
}