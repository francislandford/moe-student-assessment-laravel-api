<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hash-test', function () {
    $plainText = '123456';

    // Method 1: using hash() helper (recommended)
    $sha256 = hash('sha256', $plainText);

    // Method 2: using openssl (same result)
    // $sha256 = openssl_digest($plainText, 'sha256');

    return response()->json([
        'original'    => $plainText,
        'algorithm'   => 'sha256',
        'hash'        => $sha256,
        'length'      => strlen($sha256),          // should be 64
        'hex_length'  => strlen(bin2hex($sha256)), // also 64
    ]);
});
