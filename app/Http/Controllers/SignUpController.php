<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SignUpController extends Controller
{
    public function signUp(Request $request)
    {
        $validated = $request->validate([
            "name" => "string|min:3",
            'email' => 'email|unique:users,email',
            "password" => "string|min:8"
        ]);
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->password = Hash::make($validated['password']);

            $user->save();
            DB::commit();
            Auth::attempt(["email" => $validated["email"], "password" => $validated["password"]]);

            return response(json_encode(Auth::user()), 201)->withHeaders(["Content-Type" => "application/json"]);
        } catch (Exception $e) {
            DB::rollBack();
            return response("Something went wrong", 500)->withHeaders(["Content-Type" => "application/json"]);
        }
    }
}
