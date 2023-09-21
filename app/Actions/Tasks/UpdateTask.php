<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateTask
{
    use AsAction;

    private Model|Task $task;


    /**
     * Crear y retorna la instancia de la tarea creada.
     *
     * @param  array  $attributes Atributos del origin
     */
    public function handle(
        Task|Model|int $task,
        array $attributes
        ): Model
    {
        $attributes = collect($attributes);

        $this->task = $this->getTask($task);

        DB::transaction(function () use ($attributes) {
            $this->updateTask($attributes->only([
                'title',
                'description',
                'status',
            ])->toArray());
        });

        return $this->task;
    }

    private function getTask(Task|Model|int $task): Task|Model
    {
        if (is_int($task)) {
            $task = Task::query()->findOrFail($task);
        } elseif ($task instanceof Model) {
            $task = $task;
        } else {
            $task = Task::query()->findOrFail($task->id);
        }

        return $task;
    }

    protected function updateTask(array $attributes): void
    {
        $this->task->update([
            ...$attributes
        ]);
        
        $this->task->refresh();
    }
}