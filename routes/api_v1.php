<?php

use App\Http\Controllers\Api\V1\MainController;
use App\Services\ApiResponse;
use Illuminate\Support\Facades\Route;

Route::get('/status', [MainController::class, 'status']);
