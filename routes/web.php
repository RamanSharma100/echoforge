<?php

use Forge\core\Route;
use Forge\core\Controllers\Auth;

use App\Controllers\UserController;
use App\Controllers\ContactController;

Route::get('/', "home");

Route::get('/home', function ($request, $response) {
    return $response->render('home');
});

Route::get('/register', [UserController::class, 'register']);

Route::post('/register', [UserController::class, 'storeRegister']);

Route::get('/login', function ($request, $response) {
    return $response->render('login', [
        'errors' => [],
        'old' => []
    ]);
});

Route::post('/login', "user@store");

Route::get('/logout', function ($request, $response) {
    Auth::logout($response);
    return $response->redirect('/login');
});

Route::get('/contact', 'contact@index');

Route::get('/contact/form', [ContactController::class, 'form']);

Route::get('/about', function () {
    return "About Us";
});
