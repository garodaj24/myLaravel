<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ForAuthUsers implements Scope
{
    public function apply(Builder $query, Model $model)
    {
        $query->where('user_id', auth('api')->id());
    }
}
