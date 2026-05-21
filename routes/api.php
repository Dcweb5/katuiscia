<?php

use Illuminate\Support\Facades\Route;

require __DIR__ . '/../app/Modules/Auth/routes/auth.php';
require __DIR__ . '/../app/Modules/Product/routes/product.php';

Route::get('/health', function () {
    return response()->json(['status' => 'ok', 'app' => config('app.name')]);
});
