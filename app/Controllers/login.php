<?php

namespace App\Controllers;

use App\Models\User;


class Login
{


    public function store($request, $response)
    {
        $data = $request->getBody();

        $errors = $request->validate($request, [
            'email' => 'required|email',
            'password' => 'required|string'
        ]);

        if (count($errors) > 0) {
            return $response->render('login', [
                'errors' => $errors,
                'old' => $data
            ]);
        }

        $user = User::where('email', "=", $data['email'])->first();

        // print_r($user);
    }
}
