<?php

return [
    'private_key' => env('APPSTORECONNECT_PRIVATE_KEY'),
    'issuer_id' => env('APPSTORECONNECT_ISSUER_ID'),
    'kid' => env('APPSTORECONNECT_KID'),
    'cache_duration' => env('APPSTORECONNECT_CACHE_DURATION', 5),
    'bundle_prefix' => 'com.Talus',
    'default_tf_version' => env('APPSTORECONNECT_DEFAULT_TF_VERSION', '7.0'),
    'item_limit' => env('APPSTORECONNECT_ITEM_LIMIT', 50)
];
