<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Arr;
use Lorisleiva\Actions\Concerns\AsAction;
use Illuminate\Support\Collection;

class ListTasks
{
    use AsAction;

    protected Builder $query;

    public function handle(
        array $fields = ['*'],
        array $filters = [],
        array $sort = ['id', 'asc'],
        int $results = 10,
        bool $paginated = false,
        bool $withTrashed = false
    ): LengthAwarePaginator|Collection
    {
        $query = $this->setQuery()
            ->prepareQuery($fields, $withTrashed)
            ->applyFilter($filters);

        $results = $paginated ? $this->getPaginated($results) : $this->getCollection();

        return $results;
    }

    private function setQuery(): self
    {
        $this->query = Task::query();

        return $this;
    }

    private function prepareQuery(array $fields, bool $withTrashed): self
    {
        $this->query->select($fields)
            ->when($withTrashed, fn (Builder $builder) => $builder->withTrashed());

        return $this;
    }

    private function applyFilter(array $filters)
    {   
        $this->query
            ->when(Arr::get($filters, 'title'), function(Builder $builder) use ($filters) {
                $builder->where('title', 'like', "%{$filters['title']}%");
            });

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
