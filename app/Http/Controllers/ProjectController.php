<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Project;

class ProjectController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $datas = Project::all();
        $type = 'project';
        return view('list', compact('user', 'datas', 'type'));
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $projects = Project::where('name', 'LIKE', "%$term%")->get(['id', 'name']);
        return response()->json($projects);
    }

}
