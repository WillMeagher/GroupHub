<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CasController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
        cas()->authenticate();
    }

    public function login()
    {
        $user_email = cas()->user().'@umd.edu';
        $user = User::findByEmail($user_email);

        if (empty($user)) {
            $user = User::create(['name' => null,
                'email' => $user_email,
                'password' => null,
            ]);
            $user->markEmailAsVerified();
        }

        auth()->login($user);

        return redirect('/home');
    }
}
