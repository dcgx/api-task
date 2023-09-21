<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Lorisleiva\Actions\Concerns\AsAction;

class UpdateUser
{
    use AsAction;

    private Model|User $user;

    /**
     * Crear y retorna la instancia de la tarea creada.
     *
     * @param  array  $attributes Atributos del origin
     */
    public function handle(
        User|Model|int $user,
        array $attributes
        ): Model
    {
        $attributes = collect($attributes);

        $this->user = $this->getUser($user);

        DB::transaction(function () use ($attributes) {
            $this->updateUser($attributes->only([
                'name',
                'email',
                'password',
            ])->toArray());
        });

        return $this->user;
    }

    private function getUser(User|Model|int $user): User|Model
    {
        if (is_int($user)) {
            $user = User::query()->findOrFail($user);
        } elseif ($user instanceof Model) {
            $user = $user;
        } else {
            $user = User::query()->findOrFail($user->id);
        }

        return $user;
    }

    protected function updateUser(array $attributes): void
    {
        $this->user->update([
            ...$attributes
        ]);
        
        $this->user->refresh();
    }
}