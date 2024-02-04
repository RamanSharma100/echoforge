<?php

use Forge\core\Route;
use App\Controllers\Contact;
use Forge\core\Controllers\Auth;

Route::get('/', "home");

Route::get('/home', function ($request, $response) {
    return $response->render('home');
});

Route::get('/register', function () {
    return "Register";
});

Route::get('/login', function ($request, $response) {
    return $response->render('login', [
        'errors' => [],
        'old' => []
    ]);
});

Route::post('/login', "login@store");

Route::get('/logout', function ($request, $response) {
    Auth::logout($response);
    return $response->redirect('/login');
});

Route::get('/contact', 'contact@index');

Route::get('/contact/form', [Contact::class, 'form']);

Route::get('/about', function () {
    return "About Us";
});
