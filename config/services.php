<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key' => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    'sparkpost' => [
        'secret' => env('SPARKPOST_SECRET'),
    ],

    'stripe' => [
        'model' => App\User::class,
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
    ],

    // FNC, Inc
    'fnc' => [
        'qa' => 'https://uat.appraisalport.com/interface/',
        'live' => 'https://www.appraisalport.com/interface/',
    ],

    // ValuTrac
    'valutrac' => [
        'qa' => 'https://pcmloan-staging.myvalutrac.com/api/landmark_return.asmx',
        'live' => 'https://fam.myvalutrac.com/api/landmark_return.asmx',
    ],

    // Mercury Network
    'mercury' => [
        'status_qa' => 'https://www.mercurynetworkapiqa.com/mercuryapi.asmx/UpdateAppraisalStatusGlobal',
        'status_live' => 'https://www.mercurynetworkapi.com/mercuryapi.asmx/UpdateAppraisalStatusGlobal',
        'cc_qa' => 'https://www.mercurynetworkapiqa.com/mercuryapi.asmx/HostedMerchant',
        'cc_live' => 'https://www.mercurynetworkapi.com/mercuryapi.asmx/HostedMerchant',
    ],

    // Google API
    'google' => [
        'secret' => env('GOOGLE_API_SECRET'),
        'client_id' => env('GOOGLE_API_CLIENT_ID'),
        'redirect_url' => '/admin/integrations/google/oauth_callback', // Handle auth code
        #'redirect_url' => 'http://localhost/admin/integrations/google/oauth_callback', // for testing
        'maps' => [
          'key' => env('GOOGLE_MAPS_API_KEY'),
          'keys' => [
            env('GOOGLE_MAPS_API_KEY'),
            'AIzaSyCa288NIbN7BrH3VnmSBQIFIlP-3yBxX-U',
            'AIzaSyAsH_w0Q28tXJNhtcLsnNNdaz-TdnPwDsg',
            'AIzaSyC8FT6eUmJ7JldeT1RrTZ8pLusMijL_ERc',
            'AIzaSyDRbxGgb5NZDVj56grpIBz5UotVHj80avs'
          ]
        ],
    ],

    // Bing API
    'bing' => [
        'key' => env('BING_MAPS_API_KEY'),
    ],

    'recaptcha' => [
      // Invisible
      'key' => env('RECAPTCHA_KEY', '6Lfn1l8UAAAAAK_3HIEXwftqvhhWeOxLz0wC8phQ'),
      'secret' => env('RECAPTCHA_SECRET', '6Lfn1l8UAAAAAFXEZUsZSQtQS888e6JfwXZv7nyF')
    ]
];
