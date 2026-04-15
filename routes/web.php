<?php

use App\Http\Controllers\Api\V1\ServiceOfferController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
