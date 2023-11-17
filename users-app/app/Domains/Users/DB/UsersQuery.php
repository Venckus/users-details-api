<?php
declare(strict_types=1);

namespace App\Domains\Users\DB;

use App\Domains\Users\Requests\UserIndexData;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

/**
 * This class is a Query Model according to CQRS pattern
 * Should be used only for read operations
 */
class UsersQuery
{
    /**
     * @var array<string|string> $filters
     */
    protected array $filters = [
        'first_name',
        'last_name',
        'email',
    ];

    protected Builder $query;


    public function __construct(
        protected UserIndexData $userIndexData
    ) {
    }

    public function index(): LengthAwarePaginator
    {
        $this->query = User::query();

        $this
            ->filter()
            ->sort();

        return $this->query->paginate($this->userIndexData->per_page, ['*'], 'page', $this->userIndexData->page);
    }

    /**
     * for more complex filtering model filter class should be implemented
     */
    public function filter(): self
    {
        foreach ($this->filters as $filter) {
            if (!is_null($this->userIndexData->$filter)) {
                $this->query = $this->query->where($filter, $this->userIndexData->$filter);
            }
        }

        return $this;
    }

    /**
     * for more complex sorting other sorting approach should be choosen.
     */
    public function sort(): self
    {
        if (!is_null($this->userIndexData->sort_by) && !is_null($this->userIndexData->sort)) {
            $this->query->orderBy($this->userIndexData->sort_by, $this->userIndexData->sort);
        }

        return $this;
    }
}