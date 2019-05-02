<div class="row condition-row m-b-sm" id="condition_row_{{ $id }}" data-id="{{ $id }}">

    <div class="col-md-3">
        <div class="input-group">
          <span class="input-group-btn">
            <button type="button" class="btn btn-danger remove-condition-row"><i class="fa fa-times"></i></button>
          </span>

            {!! Form::select('conditions[' . $id . '][condition_key]',
                getList($conditionKeys, 'Condition Key'), $key, ['class' => 'form-control condition_key']
            ) !!}
        </div>
    </div>

    <div class="col-md-9">
        <div class="row">
            <div class="col-md-6 condition-match-type-div hidden">
                {!! Form::select('conditions[' . $id . '][condition_match_type]',
                    getList($conditionTypes, 'Match Type'), $type, ['class' => 'form-control']
                ) !!}
            </div>

            <div class="col-md-6 condition-value-div hidden">
                {!! Form::text('conditions[' . $id . '][condition_value]', $value,
                    ['class' => 'form-control', 'placeholder' => 'Condition Value']
                ) !!}
            </div>

            <div class="col-md-6 condition-category-div hidden">
                {!! Form::select('conditions[' . $id . '][condition_category]',
                    getList($categories->pluck('name', 'id'), 'Category'), $value, ['class' => 'form-control']
                ) !!}
            </div>
        </div>
    </div>
</div>