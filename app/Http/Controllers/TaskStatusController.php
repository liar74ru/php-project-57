<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(TaskStatus::class, 'task_status');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = TaskStatus::all();
        return view('task-status.index', ['statuses' => $statuses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('task-status.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses'
        ]);

        TaskStatus::create($data);
        flash()->success('Статус успешно создан');
        return redirect(route('task_statuses.index'));
    }

    /**
     * Display the specified resource.
     */
//    public function show(string $id)
//    {
//        //
//    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TaskStatus $task_status)
    {
        return view('task-status.edit', compact('task_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TaskStatus $task_status)
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('task_statuses')->ignore($task_status->id) // Игнорируем текущую метку
            ],
        ], [
            'name.required' => 'Это обязательное поле',
            'name.unique' => 'Статус с таким именем уже существует.'
        ]);

        $task_status->update($data);

        flash()->success('Статус успешно изменён!');

        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TaskStatus $task_status)
    {
        if ($task_status->tasks()->exists()) {
            flash()->error('Не удалось удалить статус!');
        } else {
            $task_status->delete();
            flash()->success('Статус успешно удалён!');
        }

        return redirect(route('task_statuses.index'));
    }
}
