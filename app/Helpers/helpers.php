<?php

use App\Helpers\ApiResponse;

if (!function_exists('api')) {
    function api(): ApiResponse
    {
        return app(ApiResponse::class);
    }
}
