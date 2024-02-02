<?php

namespace App\Models\SearchObject;

class BaseSearchObject
{
    public ?int $page = 1;
    public ?int $perPage = 5;

    public function __construct(array $attributes)
    {
        $this->fill($attributes);
    }

    public function fill(array $attributes)
    {
        $this->page = $attributes['page'] ?? 1;
        $this->perPage = $attributes['per_page'] ?? 4;
    }

    public function searchPaginate($modelQuery)
    {
        return $modelQuery->paginate($this->perPage, ['*'], 'page', $this->page);
    }
}
