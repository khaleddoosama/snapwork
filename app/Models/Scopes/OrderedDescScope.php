<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class OrderedDescScope implements Scope
{
    protected $column;

    public function __construct($column = 'id')
    {
        $this->column = $column;
    }

    public function apply(Builder $builder, Model $model)
    {

        $builder->orderBy($this->column, 'desc');
    }
}
