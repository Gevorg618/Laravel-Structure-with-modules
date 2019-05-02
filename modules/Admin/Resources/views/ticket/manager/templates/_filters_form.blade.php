<label>Filters:</label>

{{-- Grouped --}}
{!! Form::select('grouped', $pipelines, $request->get('grouped', 'mine'),
    ['id' => 'grouped', 'multiple' => 'multiple', 'class' => 'form-control filter-change bootstrap-multiselect']
) !!}

{{-- Open/Close --}}
{!! Form::select('open_or_close', getList(['open' => 'Open', 'closed' => 'Closed'], 'All'),
    $request->get('open_or_close', 'open'),
    ['id' => 'open_or_close', 'class' => 'form-control filter-change bootstrap-multiselect']
) !!}

{{-- Status --}}
{!! Form::select('status', $statuses->pluck('name', 'id'), $request->get('status'),
    ['id' => 'status', 'class' => 'form-control filter-change bootstrap-multiselect', 'multiple' => 'multiple']
) !!}

{{-- Category --}}
{!! Form::select('category', $categories->pluck('name', 'id'), $request->get('category'),
    ['id' => 'category', 'class' => 'form-control filter-change bootstrap-multiselect', 'multiple' => 'multiple']
) !!}

{{-- Priority --}}
{!! Form::select('priority', getList($priorities, 'All Priorities'), $request->get('priority'),
    ['id' => 'priority', 'class' => 'form-control filter-change bootstrap-multiselect']
) !!}

{{-- Timezone--}}
{!! Form::select('timezone', getRegions(), $request->get('timezone'),
    ['id' => 'timezone', 'multiple' => 'multiple', 'class' => 'form-control filter-change bootstrap-multiselect']
) !!}

<button class="btn btn-default" id="refresh-tickets" name="refresh-tickets">Refresh</button>