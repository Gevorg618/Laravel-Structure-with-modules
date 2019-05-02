{!! Form::checkbox('ids[' . $id . ']', $id, false,
    ['id' => 'ids[' . $id . ']', 'class' => 'ticket-ids regular-checkbox']
) !!}
<label for="ids_{{ $id }}"></label>