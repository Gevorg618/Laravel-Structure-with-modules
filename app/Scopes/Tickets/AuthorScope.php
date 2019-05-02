<?php

namespace App\Scopes\Tickets;

use App\Models\Ticket\Ticket;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class AuthorScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model)
    {
      $builder->where($model->qualifyColumn('userid'), user()->id)
              ->orWhere($model->qualifyColumn('from_content'), user()->email);
    }
}