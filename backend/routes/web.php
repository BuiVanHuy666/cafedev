<?php

use App\Http\Controllers\Api\CategoryController;

Route::get('api/categories', CategoryController::class)->middleware('throttle:public-api');
