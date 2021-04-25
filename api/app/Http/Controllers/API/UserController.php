<?php


namespace App\Http\Controllers\API;


use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('show');
    }

    public function show()
    {
        return response()->json(auth()->user());
    }
}
