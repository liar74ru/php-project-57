<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

//    public mixed $labels;
    protected $fillable = [
        'name',
        'description',
        'status_id',
        'created_by_id',
        'assigned_to_id'
    ];
    // Связь со статусом
    public function status(): BelongsTo
    {
        return $this->belongsTo(TaskStatus::class);
    }

    // Связь с создателем задачи
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Связь с исполнителем
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return BelongsToMany
     */
    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(Label::class);
    }

    public function make (): Task
    {
        return $this->created_by_id = Auth::id();
    }
}
