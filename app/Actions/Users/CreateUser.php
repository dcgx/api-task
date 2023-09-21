<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class CreateUser
{
    use AsAction;

    private Model|User $user;


    /**
     * Crear y retorna la instancia de la tarea creada.
     *
     * @param  array  $attributes Atributos del origin
     */
    public function handle(array $attributes): Model
    {
        $attributes = collect($attributes);

        DB::transaction(function () use ($attributes) {
            $this->createUser($attributes->only([
                'name',
                'password',
                'email',
            ])->toArray());
        });

        return $this->user;
    }

    protected function createUser(array $attributes): void
    {
        $this->user = User::query()->create($attributes);
    }
}