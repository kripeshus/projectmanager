<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TimeEntry;
use App\Models\Task;
use App\Models\Project;

class TimeEntryController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $datas = TimeEntry::with(['project', 'task', 'user'])->get();
        $type = 'time_entry';
        return view('list', compact('user', 'datas', 'type'));
    }

    public function time(Request $request)
    {
        $request->validate([
            'project' => 'required',
            'task' => 'required',
            'date' => 'required',
            'hours' => 'required|integer',
            'description' => 'required',
        ]);
        $time = new TimeEntry();
        if (!empty($request->project_id) && !empty($request->task_id)) {
            $time->project_id = $request->project_id;
            $time->task_id = $request->task_id;
        } else if(!empty($request->project_id)){
            $task = new Task();
            $task->project_id = $request->project_id;
            $task->name = $request->task;
            $task->save();
            $time = new TimeEntry();
            $time->project_id = $request->project_id;
            $time->task_id = $task->id;
        } else {
            $project = new Project();
            $project->name = $request->project;
            $project->save();
            $task = new Task();
            $task->project_id = $project->id;
            $task->name = $request->task;
            $task->save();
            $time = new TimeEntry();
            $time->project_id = $project->id;
            $time->task_id = $task->id;
        }
        $time->date = $request->date;
        $time->hours = $request->hours;
        $time->user_id = Auth::id();
        $time->description = $request->description;
        $time->save();
        return response()->json(['message' => 'Time Entry added succesfully'], 200);
        
    }

    public function report()
    {
        $projects = Project::all();
        return view('report', compact('projects'));
    }

    public function getReport(Request $request)
    {
        $query = Project::with(['tasks.timeEntries' => function($q) {
            $q->selectRaw('task_id, sum(hours) as total')->groupBy('task_id');
        }]);

        if ($request->filled('project_id')) {
            $query->where('id', $request->project_id);
        }

        $projects = $query->get();

        $report = [];
        foreach ($projects as $project) {
            $projectTotal = 0;
            $taskList = [];
            foreach ($project->tasks as $task) {
                $hours = $task->timeEntries->first()->total ?? 0;
                $projectTotal += $hours;
                if ($hours > 0) {
                    $taskList[] = [
                        'name' => $task->name,
                        'hours' => $hours
                    ];
                }
            }
            if ($projectTotal > 0) {
                $report[] = [
                    'project' => $project->name,
                    'total' => $projectTotal,
                    'tasks' => $taskList
                ];
            }
        }

        return response()->json($report);
    }

}
