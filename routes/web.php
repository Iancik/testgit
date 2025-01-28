<?php

use Illuminate\Support\Facades\Route;

Route::get('/practica', function () {
    return 'aaaaa';
});
use App\Http\Controllers\MyPlaceController;
Route::get('/my_page', [MyPlaceController::class, 'index']);
