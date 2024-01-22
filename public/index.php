<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\Contact;
use Forge\core\Application as CoreApplication;


$app = new CoreApplication();

$app->router->get('/', "home");

$app->router->get('/contact', 'contact@index');

$app->router->get('/contact/form', [Contact::class, 'form']);

$app->router->get('/about', function () {
    return "About Us";
});

$app->run();
