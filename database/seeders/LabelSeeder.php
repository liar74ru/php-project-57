<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Label;

class LabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $statuses = [
            'bug' => 'Какая-то ошибка в коде или проблема с функциональностью',
            'документация' => 'Задача которая касается документации',
            'дубликат' => 'Повтор другой задачи',
            'доработка' => 'Новая фича, которую нужно запилить'
        ];
        foreach ($statuses as $name => $description) {
            Label::firstOrCreate(['name' => $name, 'description' => $description]);
        }
    }
}
