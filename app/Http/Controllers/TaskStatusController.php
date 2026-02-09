<?php

namespace App\Http\Controllers;

use App\Models\TaskStatus;
use Illuminate\Http\Request;

class TaskStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
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
    public function edit(string $id)
    {
        $task_status = TaskStatus::findOrFail($id);
        return view('task-status.edit', compact('task_status'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255|unique:task_statuses,name,' . $id
        ], [
            'name.required' => 'Это обязательное поле',
            'name.unique' => 'Статус с таким именем уже существует.'
        ]);

        $taskStatus->update($data);

        flash()->success('Статус успешно изменён!');

        return redirect()->route('task_statuses.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $taskStatus = TaskStatus::findOrFail($id);

        if ($taskStatus->tasks()->exists()) {
            flash()->error('Не удалось удалить статус');
            return redirect(route('task_statuses.index'));
        }

        $taskStatus->delete();
        flash()->info('Статус успешно удалён!');
        return redirect(route('task_statuses.index'));
    }
}
