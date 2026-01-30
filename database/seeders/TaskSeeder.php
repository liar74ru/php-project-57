<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskStatus;
use App\Models\User;
use App\Models\Task;

class TaskSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        {
            // Получаем всех пользователей и статусы
            $users = User::all();
            $statuses = TaskStatus::all();

            // Если нет пользователей или статусов, создаем сначала их
            if ($users->isEmpty()) {
                $this->call(UserSeeder::class);
                $users = User::all();
            }

            if ($statuses->isEmpty()) {
                $this->call(TaskStatusSeeder::class);
                $statuses = TaskStatus::all();
            }

            // Массив примерных задач (как на скриншоте)
            $tasks = [
                [
                    'name' => 'Исправить ошибку в какой-нибудь строке',
                    'description' => 'Найти и исправить синтаксическую ошибку в коде',
                ],
                [
                    'name' => 'Дополнить дизайн главной страницы',
                    'description' => 'Добавить новые элементы и улучшить UI',
                ],
                [
                    'name' => 'Отрефакторить авторизацию',
                    'description' => 'Улучшить код модуля авторизации',
                ],
                [
                    'name' => 'Доработать команду подготовки БД',
                    'description' => 'Расширить функционал миграций и сидеров',
                ],
                [
                    'name' => 'Пофиксить вон ту кнопку',
                    'description' => 'Исправить баг с кликом на кнопку',
                ],
                [
                    'name' => 'Исправить поиск',
                    'description' => 'Оптимизировать поисковый алгоритм',
                ],
                [
                    'name' => 'Добавить интеграцию с облаками',
                    'description' => 'Реализовать загрузку файлов в облачные хранилища',
                ],
                [
                    'name' => 'Выполнить лишние зависимости',
                    'description' => 'Убрать неиспользуемые зависимости из проекта',
                ],
                [
                    'name' => 'Запилить сертификаты',
                    'description' => 'Настроить SSL/TLS сертификаты',
                ],
                [
                    'name' => 'Выполнить игру престолов',
                    'description' => 'Реализовать фичи из списка требований',
                ],
                [
                    'name' => 'Пофиксить спеку во всех репозиториях',
                    'description' => 'Обновить спецификации API',
                ],
                [
                    'name' => 'Вернуть крошки',
                    'description' => 'Восстановить хлебные крошки навигации',
                ],
                [
                    'name' => 'Установить Linux',
                    'description' => 'Настроить сервер на Linux',
                ],
                [
                    'name' => 'Потребовать прибавки к зарплате',
                    'description' => 'Подготовить отчет для руководства',
                ],
                [
                    'name' => 'Добавить поиск по фото',
                    'description' => 'Реализовать поиск по изображениям',
                ],
            ];

            // Создаем задачи
            foreach ($tasks as $index => $taskData) {
                $creator = $users->random();
                $assignee = rand(0, 1) ? $users->random() : null; // 50% шанс что задача назначена

                Task::create([
                    'name' => $taskData['name'],
                    'description' => $taskData['description'],
                    'status_id' => $statuses->random()->id,
                    'created_by_id' => $creator->id,
                    'assigned_to_id' => $assignee?->id,
                    'created_at' => now()->subDays(rand(1, 30)), // случайная дата за последний месяц
                ]);
            }

            $this->command->info('Создано ' . count($tasks) . ' задач');
        }
    }
}
