<?php

namespace App\Policies;

use App\Models\TaskStatus;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaskStatusPolicy
{
    use HandlesAuthorization;

    public function viewAny(?User $user): bool
    {
        // Любой пользователь может просматривать статусы
        return true;
    }

    public function view(?User $user, TaskStatus $taskStatus): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        // Только авторизованные пользователи могут создавать статусы
        return $user !== null;
    }

    public function update(User $user, TaskStatus $taskStatus): bool
    {
        // Любой авторизованный пользователь может редактировать статусы
        return $user !== null;
    }

    public function delete(User $user, TaskStatus $taskStatus): bool
    {
        // Проверяем, что статус не используется в задачах
        return $user !== null;
    }
}
