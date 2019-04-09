<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class NotDeletedScope implements Scope
{
    /**
     * Apply the scope to a given query builder
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('deleted', 0);
    }
}