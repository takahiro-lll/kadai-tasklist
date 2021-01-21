<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Task;
use App\User;
use Illuminate\Support\Facades\Auth;

class TasksController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    { 
        $tasks =[];
        if (\Auth::check()) {
        $user = User::findOrFail(Auth::id());
        $tasks = $user->tasks()->get();
        }
        return view('tasks.index', [ 'tasks' => $tasks, ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $task = new Task;
        
        return view('tasks.create', ['task' => $task, ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255'
        ]);
            
        $request->user()->tasks()->create([
            'content' => $request->content, 'status' => $request->status
        ]);
        
        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $task = Task::findOrFail($id);
        if (Auth::id() === $task->user_id) {
        return view('tasks.show', ['task' => $task, ]);
        }
        else {
             return redirect('/');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $task = Task::findOrFail($id);
        if (Auth::id() === $task->user_id) {
        return view('tasks.edit', ['task' => $task, ]);
        }
        else {
             return redirect('/');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|max:10',
            'content' => 'required|max:255'
            ]);
            
        
        $task = Task::findOrFail($id);
        $task->content = $request->content;
        $task->status = $request->status;
        if (Auth::id() === $task->user_id) {
        $task->save();
        }
        
        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       
        $task = Task::findOrFail($id);
        if (Auth::id() === $task->user_id) {
        $task->delete();
        }
        
        return redirect('/');
    }
}
