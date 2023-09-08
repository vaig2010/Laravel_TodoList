<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Repositories\TaskRepository;
use Composer\Util\Http\Response;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class TaskController extends Controller
{
    protected $tasks;
//
//    public function forUser(User $user)
//    {
//        return Task::where('user_id', $user->id)
//            ->orderBy('created_at', 'asc')
//            ->get();
//    }
    public function __construct(TaskRepository $tasks)
    {
        $this->middleware('auth');

        $this->tasks = $tasks;
    }

    public function index(Request $request)
    {
        return view('index', [
            'tasks' => $this->tasks->forUser($request->user()),
        ]);
    }

    /**
     * Create a new task.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:255',
        ]);

        $request->user()->tasks()->create([
            'name' => $request->name,
        ]);

        return redirect('/tasks');
    }

    /**
     * Destroy the given task.
     *
     * @param  Request  $request
     * @param  Task  $task
     * @return Response
     */
    public function destroy(Request $request, Task $task)
    {
        $this->authorize('destroy', $task);

        $task->delete();

        return redirect('/tasks');
    }
}
