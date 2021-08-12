<?php

namespace Domain\User\Controllers;

use App\Http\Controllers\Controller;
use Domain\User\User;
use Exception;
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

        try {
            User::create(request(['name', 'email', 'password']));
            return response()->json(true);
        }
        catch(Exception $e) {
            return response()->json(false);
        }

    }
}
