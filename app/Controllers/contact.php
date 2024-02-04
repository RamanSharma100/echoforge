<?php

namespace App\Controllers;

use Forge\core\Controllers\Controller;


class Contact extends Controller
{

    public function index()
    {
        return $this->view('contact');
    }

    public function form()
    {
        return "Contact Form";
    }
}
