<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function login(Request $request)
    {
       $validated = $request->validate([
        'email'=>'string|required',
        'password'=>'string|required'
       ]);
       if(Auth::attempt($validated)){
        return response(json_encode(Auth::user(),200))->withHeaders([
            'Content-Type'=>'application/json'
        ]);
       }
       else {
        return response('failed to login',400);
       }
    }

}
