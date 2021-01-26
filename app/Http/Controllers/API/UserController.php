<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        // $user = auth('api')->user();
        $users = User::all();
        return response()->json($users->toArray());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:App\Models\User,email|email:rfc,dns',
            'password' => 'required|string|max:255'
        ]);
        
        $user = auth('api')->user();
        $newUser = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($newUser);
    }

    public function show(User $user)
    {
        return $user;
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|unique:App\Models\User,email|email:rfc,dns',
            'password' => 'required|string|max:255'
        ]);

        // abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        // abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");
        $user->delete();
        return response()->json($user);
    }
}