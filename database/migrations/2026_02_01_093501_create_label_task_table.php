<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
            Schema::create('label_task', function (Blueprint $table) {
                $table->id();
            // Внешние ключи
                $table->foreignId('task_id')
                ->constrained() // Ссылается на таблицу tasks, поле id
                ->onDelete('cascade'); // При удалении задачи удаляются все связи

                $table->foreignId('label_id')
                ->constrained() // Ссылается на таблицу labels, поле id
                ->onDelete('cascade'); // При удалении метки удаляются все связи

            // Уникальная комбинация task_id + label_id
            // Нельзя привязать одну и ту же метку к задаче дважды
                $table->unique(['task_id', 'label_id']);
                $table->timestamps();
            });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('label_task');
    }
};
