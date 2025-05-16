<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\UserController;


Route::post('/users', [UserController::class, 'store']);
#Route::get('emslogin', [UserController::class, 'emslogin']);