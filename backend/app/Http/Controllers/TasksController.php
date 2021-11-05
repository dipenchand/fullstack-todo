<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TasksController extends Controller
{
    public function getTasks()
    {
        $getTasks = Task::orderBy('id', 'desc')->get();

        return response()->json([
            'result' => $getTasks
        ]);
    }

    public function storeTask(Request $request)
    {
        $task = $request['task'];
        Task::create([
            'name' => $task
        ]);
        return response()->json([
            'success' => true
        ]);

    }

    public function completeTask(Request $request)
    {
        $id = $request['taskId'];
        $task = Task::findOrFail($id);
        $task->update([
            'status' => 1
        ]);
        return response()->json([
            'success' => true
        ]);
    }

    public function updateTask(Request $request)
    {
        $id = $request['id'];
        $newTask = $request['newTask'];

        $task = Task::findOrFail($id);
        $task->update([
            'name' => $newTask
        ]);
        return response()->json([
            'success' => true
        ]);
    }
}
