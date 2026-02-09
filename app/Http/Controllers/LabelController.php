<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class LabelController extends Controller
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
        $labels = Label::all();
        return view('label.index', compact('labels'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('label.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:labels',
            'description' => 'nullable|string|max:500'
        ]);

        Label::create($data);
        flash()->success('Метка успешно создана');
        return redirect(route('labels.index'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $label = Label::findOrFail($id);
        return view('label.edit', compact('label'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $label = Label::findOrFail($id);
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('labels')->ignore($label->id) // Игнорируем текущую метку
            ],
            'description' => 'nullable|string|max:500'
        ]);

        $label->update($data);
        flash()->success('Метка успешно изменена');
        return redirect(route('labels.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $label = Label::findOrFail($id);

        if ($label->tasks()->exists()) {
            flash()->error('Не удалось удалить метку');
            return redirect(route('labels.index'));
        }
        $label->delete();
        flash()->success('Метка успешно удалена');
        return redirect(route('labels.index'));
    }
}
