<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'name' => 'Book Library API',
        'documentation_url' => url('/api/documentation'),
    ], 200, [], JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
});
