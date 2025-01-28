<?php

use Illuminate\Support\Facades\Route;

Route::get('/practicaaaaaa', function () {
    return 'aaaaa';
});
use App\Http\Controllers\MyPlaceController;
Route::get('/my_page', [MyPlaceController::class, 'index']);
