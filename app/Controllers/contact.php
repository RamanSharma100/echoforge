<?php

namespace App\Controllers;

use function Forge\core\view;

class Contact
{

    public function index()
    {
        view('contact');
    }

    public function form()
    {
        echo "Contact Form";
    }
}
