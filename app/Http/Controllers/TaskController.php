<?php

namespace App\Http\Controllers;

use App\Models\Label;
use App\Models\Task;
use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
//    public function __construct()
//    {
//        $this->middleware('auth')->except(['index', 'show']);
//    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $statuses = TaskStatus::all();
        $tasks = Task::paginate(15);
        $users = User::all();
        return view('task.index', compact('tasks', 'statuses', 'users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $statuses = TaskStatus::all();
        $users = User::all();
        $labels = Label::all();
        return view('task.create', compact('statuses', 'users', 'labels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Валидация основных полей
        $taskData = $request->validate([
            'name' => 'required|string|max:255|unique:tasks',
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id',
        ]);

        // Валидация меток отдельно
        $labelsData = $request->validate([
            'labels' => 'nullable|array',
            'labels.*' => 'integer|exists:labels,id',
        ]);

        // Добавляем создателя
        $taskData['created_by_id'] = Auth::id();

        // Создаем задачу
        $task = Task::create($taskData);

        // Прикрепляем метки
        if (!empty($labelsData['labels'])) {
            $task->labels()->attach($labelsData['labels']);
        }

        flash()->success('Задача создана!');
        return redirect()->route('tasks.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        $task->load(['labels', 'status', 'creator', 'assignee']);
        $labels = Label::all();
        return view('task.show', compact('task', 'labels'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        // Жадная загрузка отношений задачи
        $task->load(['status', 'creator', 'assignee', 'labels']);

        // Все доступные метки для выбора в форме
        $allLabels = Label::orderBy('name')->get();

        // ID уже прикреплённых меток (для pre-select в форме)
        $attachedLabelIds = $task->labels->pluck('id')->toArray();

        // Данные для выпадающих списков
        $statuses = TaskStatus::all();
        $users = User::all();

        return view('task.edit', compact(
            'task',
            'allLabels',
            'attachedLabelIds',
            'statuses',
            'users'
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tasks')->ignore($task->id, 'id'),
            ],
            'description' => 'nullable|string',
            'status_id' => 'required|exists:task_statuses,id',
            'assigned_to_id' => 'nullable|exists:users,id'
        ]);

        $task->update($data);

        $labelsData = $request->validate([
            'labels' => 'nullable|array',
            'labels.*' => 'integer|exists:labels,id',
        ]);

        // Прикрепляем метки
        $task->labels()->sync($labelsData['labels'] ?? []);

        flash()->success('Задача изменена!');

        return redirect()->route('tasks.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Task::destroy($id);
        flash()->info('Задача удалена!');
        return redirect(route('tasks.index'));
    }
}
