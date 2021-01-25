<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = auth('api')->user();
        $todos = $user->todos()->get(['id', 'name', 'completed_at', 'user_id', 'created_at']);
        return response()->json($todos->toArray());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);
        
        $user = auth('api')->user();
        $todo = $user->todos()->create([
            'name' => $request->name
        ]);

        return response()->json($todo);
    }

    public function show(Todo $todo)
    {
        return $todo;
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");

        $todo->update([
            "name" => $request->name,
        ]);

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");
        $todo->delete();
        return response()->json($todo);
    }
}
