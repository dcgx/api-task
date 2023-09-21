<?php

declare(strict_types=1);

namespace App\Actions\Tasks;

use App\Models\Task;
use App\Models\User;
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
        int $results = 10,
        bool $paginated = false,
        bool $withTrashed = false
    ): LengthAwarePaginator|Collection
    {
        $user = auth()->user();
        $query = $this->setQuery()
            ->prepareQuery($fields, $withTrashed, $user)
            ->applyFilter($filters);

        $results = $paginated ? $this->getPaginated($results) : $this->getCollection();

        return $results;
    }

    protected function setQuery(): self
    {
        $this->query = Task::query();

        return $this;
    }

    protected function prepareQuery(array $fields, bool $withTrashed, User $user): self
    {
        $this->query->select($fields)
            ->when($withTrashed, fn (Builder $builder) => $builder->withTrashed());

        $this->query->where('user_id', $user->id);

        return $this;
    }

    protected function applyFilter(array $filters)
    {   
        $this->query
            ->when(Arr::get($filters, 'title'), function(Builder $builder) use ($filters) {
                $builder->where('title', 'like', "%{$filters['title']}%");
            })
            ->when(Arr::get($filters, 'description'), function(Builder $builder) use ($filters) {
                $builder->where('description', 'like', "%{$filters['description']}%");
            })
            ->when(Arr::get($filters, 'status'), function(Builder $builder) use ($filters) {
                $status = $filters['status'] === 'true' || $filters['status'] === '1' || $filters['status'] === 1 ? 1 : 0;  
                $builder->where('status', $status);
            });

        return $this;
    }

    protected function applySort(array $sort): self
    {
        $this->query->orderBy($sort[0], $sort[1]);

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
