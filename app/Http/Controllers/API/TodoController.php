<?php

namespace App\Http\Controllers\API;

use App\Models\Category;
use App\Models\Todo;
use App\Http\Resources\TodoResource;
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
        return TodoResource::collection(Todo::orderBy("id", "desc")->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        $user = auth('api')->user();
        $todo = $user->todos()->create([
            'name' => $request->name,
        ]);

        $todo->categories()->sync($request->categories);

        return TodoResource::make($todo->refresh());
    }

    public function show(Todo $todo)
    {
        return $todo;
    }

    public function update(Request $request, Todo $todo)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'categories'   => 'array',
            'categories.*' => 'exists:categories,id'
        ]);

        $todo->update([
            "name" => $request->name,
        ]);

        $todo->categories()->sync($request->categories);

        return TodoResource::make($todo->refresh());
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();
        return response()->json($todo);
    }

    public function complete(Request $request, Todo $todo)
    {
        $request->validate([
            'completed' => 'required|boolean'
        ]);

        $todo->update([
            "completed_at" => $request->completed ? Carbon::now() : null
        ]);

        return response()->json($todo);
    }

    public function toggleCategories(Request $request, Todo $todo)
    {
        $request->validate([
            'categories'   => 'required|array',
            'categories.*' => 'required|exists:categories,id'
        ]);


//        dd(Category::whereIn('id', $request->categories)->get()->toArray());
//
    //    dd($request->all());

//        $user->todo()->create([]); // Simple relations

        // 1. $todo->categories()->sync($request->categories); // sync add or remove all in that array
        // 2. $todo->categories()->attach($request->categories); // always add
        // 3. $todo->categories()->detach($request->categories); // always delete
        // 4. $todo->categories()->syncWithoutDetaching($request->categories) // only add without delete

        
        // 1. $todo->categories; // get todo categories
        // 2. $todo->categories()->get() // get todo categories
        
        // 1. $todo->categories()->exists() // check if exists some Model relationship
        
        //
        //        $todos = Todo::where('created_at', '<=', Carbon::today()->subDay())
        //            ->whereNull('completed_at')
        //            ->has('categories') // ->doesntHave('categories')
        //            ->whereHas('user', function($query) {
            //                $query->where('is_admin', true); // will be applied to users table rows
            //            })
            //            ->orderBy('created_at', 'desc')
            //            ->get();
            //
            //        dd($todos->toArray());
            
        $todo->categories()->sync($request->categories);

        return $todo->categories;

    }

    public function getCategories()
    {
        return response()->json(Category::all());
    }
}
