<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateTask
{
    use AsAction;

    private Model|Task $task;


    /**
     * Crear y retorna la instancia de la tarea creada.
     *
     * @param  array  $attributes Atributos del origin
     */
    public function handle(array $attributes): Model
    {
        $attributes = collect($attributes);

        DB::transaction(function () use ($attributes) {
            $this->createTask($attributes->only([
                'title',
                'description',
                'status',
                'user_id',
            ])->toArray());
        });

        return $this->task;
    }

    protected function createTask(array $attributes): void
    {
        $this->task = Task::query()->create($attributes);
    }
}