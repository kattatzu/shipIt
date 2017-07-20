<?php

return array(
    'email' => env('SHIPIT_EMAIL'),
    'token' => env('SHIPIT_TOKEN'),
    'callback_url' => env('SHIPIT_CALLBACK_URL', 'callback/shipit')
);