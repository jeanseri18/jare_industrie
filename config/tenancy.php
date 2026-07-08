<?php

return [
    'base_domain' => env('APP_BASE_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),

    'default_subdomain' => env('APP_DEFAULT_ORG_SUBDOMAIN', 'jare'),

    'storage_path' => 'organizations',
];
