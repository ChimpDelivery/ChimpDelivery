<?php

return [
    'endpoint' => 'https://api.appstoreconnect.apple.com/v1',

    'cache_duration' => env('APPSTORECONNECT_CACHE_DURATION', 5),
    'item_limit' => env('APPSTORECONNECT_ITEM_LIMIT', 50),
];
