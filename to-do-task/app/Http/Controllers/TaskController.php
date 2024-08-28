<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
		 $tasks = Task::where('status', 'Non completed')->get();

    return view('tasks.index', compact('tasks'));
	
		 // $tasks = Task::latest()->get();
    // return view('tasks.index', compact('tasks'));
    }
	
	public function show()
    {
        $tasks= Task::all();
	    return view('tasks.show', compact('tasks'));
    }

    public function store(Request $request)
    {
		$request->validate([
        'title' => 'required|string|max:255|unique:tasks,title',
    ]);
	
        $task = Task::create($request->all());
        return response()->json($task);
    }

    public function edit($id)
    {
        $task = Task::find($id);
        return response()->json($task);
    }

    public function update(Request $request, $id)
    {
		$task = Task::find($id);
        $task->update($request->all());
        return response()->json($task);
    }
	
	public function complete(Request $request, Task $task)
{
    $task->status = 'completed';
    $task->save();

    return response()->json([
        'success' => true,
        'message' => 'Task marked as completed!',
        'task_id' => $task->id,
    ]);
}

    public function destroy($id)
    {
        task::find($id)->delete();
        return response()->json(['success' => 'Task deleted successfully.']);
    }
}
