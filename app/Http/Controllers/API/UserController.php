<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::orderBy("id", "desc")->get();
        return response()->json($users->toArray());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|max:255|email:rfc,dns|unique:users,email',
            'password' => 'required|string|max:255'
        ]);
        
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
            'email' => 'required|string|max:255|email:rfc,dns|unique:users,email,'.$user->id,
            'password' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $fileName = (string) Str::uuid() . '.' . $image->getClientOriginalExtension();

            if ($image->getClientOriginalName() !== $user->image->original_file_name) {
                $user->image()->delete();
                Storage::disk('public')->delete("images/$user->id"."/".$user->image->storage_uuid);
                $user->image()->create([
                    'storage_uuid' => $fileName,
                    'original_file_name' => $image->getClientOriginalName(),
                ]);
                $image->storeAs("images/$user->id", $fileName, 'public');
            }


        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->has('password') ? bcrypt($request->password) : $user->password,
            'image' => $request->image
        ]);

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $user->delete();
        return response()->json($user);
    }
}