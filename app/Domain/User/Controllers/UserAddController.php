<?php

namespace Domain\User\Controllers;

use App\Http\Controllers\Controller;
use Domain\User\User;
use Illuminate\Http\Request;

class UserAddController extends Controller
{
    /**
     * Provision a new web server.
     *
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]);

        $user = User::create(request(['name', 'email', 'password']));

        auth()->login($user);
    }
}
