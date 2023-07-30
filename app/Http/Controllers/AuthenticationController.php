<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Exception\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use PhpParser\NodeVisitor\FirstFindingVisitor;

class AuthenticationController extends Controller
{
    public function login(Request $request) //kenapa meggunakan Request Karna akan mereques inputan
    {
        //validasi login
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        $user = User::where('email', $request->email)->First();

        //pengecekan login bener atau tidak email dan password yang di inputkan
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationValidationException::withMessages([
                'email' => ['email atau password yang diinputkan tidak sesuai.'],
            ]);
        }
        return $user->createToken('user login')->plainTextToken;
    }

    //remove token
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout Berhasil.']);
    }

    //method atau function me ini fungsinya untuk mengetahui user siapa yang login
    public function me (Request $request)
    {
        return response()->json(Auth::user());
    }
}