<?php

namespace App\Http\Controllers\API;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use Carbon\Carbon;

class TodoController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index()
    {
        $user = auth('api')->user();
        $todos = $user->todos()->orderBy("id", "desc")->get();
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

        return response()->json($todo->refresh());
    }

    public function show(Todo $todo)
    {
        abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");
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

        return response()->json($todo->refresh());
    }

    public function destroy(Todo $todo)
    {
        abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");
        $todo->delete();
        return response()->json($todo);
    }

    public function complete(Request $request, Todo $todo)
    {
        $request->validate([
            'completed' => 'required|boolean'
        ]);

        abort_if($todo->user_id !== auth('api')->id(), 403, "Unauthorized");

        $todo->update([
            "completed_at" => $request->completed ? Carbon::now() : null
        ]);
        
        return response()->json($todo);
    }
}
