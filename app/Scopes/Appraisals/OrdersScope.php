<?php

namespace App\Scopes\Appraisals;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class OrdersScope implements Scope
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
        if(isVendor()) {
          $builder->where($model->qualifyColumn('acceptedby'), user()->id);
        } else if (isClient()) {
          $builder->where($model->qualifyColumn('orderedby'), user()->id);
        } else {
          // Nothing yet
        }
    }
}