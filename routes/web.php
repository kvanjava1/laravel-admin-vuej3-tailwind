<?php
use Illuminate\Support\Facades\Route;

Route::get('/cms/{any?}', function () {
    return view('cms');
})->where('any', '.*');
