<?php


namespace App\Http\Controllers\Auth;


use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:6',
            'confirm_password'  => 'required|same:password'
        ]);

        User::create([
            'name'  => $request->get('name'),
            'email'  => $request->get('email'),
            'password'  => bcrypt( $request->get('password') ),
        ]);

        return response()->json('', 204);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required'
        ]);

        if ( Auth::attempt([
            'email'     => $request->get('email'),
            'password'  => $request->get('password')
        ])) {
            return response()->json('', 204);
        }

        return response()->json([
            'error' => 'invalid_credentials'
        ], 401);
    }

    public function logout()
    {
        Auth::guard('web')->logout();

        return response()->json('', 204);
    }
}
