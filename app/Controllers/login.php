<?php

namespace App\Controllers;

class Login
{

    public function store($request, $response)
    {
        $data = $request->getBody();
        echo "<pre>";
        var_dump($data);
        echo "</pre>";
    }
}
