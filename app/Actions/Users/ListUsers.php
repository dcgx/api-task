<?php

declare(strict_types=1);

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Collection;

class ListUsers
{
    use AsAction;

    protected Builder $query;

    public function handle(
        int $results = 10,
        bool $paginated = false,
        bool $withTrashed = false
    ): LengthAwarePaginator|Collection
    {
        $user = auth()->user();
        $query = $this->setQuery()
            ->prepareQuery($withTrashed);

        $results = $paginated ? $this->getPaginated($results) : $this->getCollection();

        return $results;
    }

    protected function setQuery(): self
    {
        $this->query = User::query();

        return $this;
    }

    protected function prepareQuery(bool $withTrashed): self
    {
        $this->query->when($withTrashed, fn (Builder $builder) => $builder->withTrashed());

        return $this;
    }

    private function getPaginated(int $results): LengthAwarePaginator
    {
        return $this->query->paginate($results);
    }

    private function getCollection(): Collection
    {
        return $this->query->get();
    }
}
