<?php

return [
    'private_key' => env('APPSTORECONNECT_PRIVATE_KEY', ''),
    'issuer_id' => env('APPSTORECONNECT_ISSUER_ID', ''),
    'kid' => env('APPSTORECONNECT_KID', ''),
    'cache_duration' => env('APPSTORECONNECT_CACHE_DURATION', 5),
    'bundle_prefix' => env('APPSTORECONNECT_BUNDLE_PREFIX', 'com.CompanyName'),
    'item_limit' => env('APPSTORECONNECT_ITEM_LIMIT', 50),
    'user_email' => env('APPSTORECONNECT_USER_EMAIL', ''),
    'user_pass' => env('APPSTORECONNECT_USER_PASS', ''),
    'company_name' => env('APPSTORECONNECT_COMPANY_NAME', 'DemoCompany')
];
