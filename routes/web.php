<?php

use Forge\core\Route;
use App\Controllers\Contact;

Route::get('/', "home");

Route::get('/login', "login");

Route::post('/login', "login@store");

Route::get('/contact', 'contact@index');

Route::get('/contact/form', [Contact::class, 'form']);

Route::get('/about', function () {
    return "About Us";
});
