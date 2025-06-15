<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $datas = Task::with('project')->get();
        //dd($tasks->toArray());
        $type = 'task';
        return view('list', compact('user', 'datas', 'type'));
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $tasks = Task::where('name', 'LIKE', "%$term%")->get(['id', 'name']);
        return response()->json($tasks);
    }
}
