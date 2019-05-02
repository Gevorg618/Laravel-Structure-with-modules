@php
    $value = $collection->answer_value && $collection->answer_value !== '' ? $collection->answer_value : $collection->default_value;
    $values = $collection->answer_value && $collection->answer_value !== '' ? explode(',', $collection->answer_value->value) : explode(',', $collection->default_value);
@endphp

@if($collection->field_type === 'checkbox')
    @php
        $checkedValues = $answer_value && $answer_value != '' ? explode(',', $answer_value) : explode(',', $collection->default_value);
    @endphp
    @if($collection->hasList)
        <input type="hidden" name="collection[{{$collection->id}}]" value="0"/>

        @foreach($collection->hasList as $key => $value)
            <div class="checkbox">
                <label for="collection[{{$key}}]">
                    <input type="checkbox" name="collection[{{$collection->id}}]"
                           id="collection_{{$key}}_{{$collection->id}} {{$checkedValues && in_array($key, $checkedValues) ? 'checked' : ''}}">
                </label>
            </div>
        @endforeach
    @else
        @php
            $val = $answer_value !== '' ? $answer_value : $ollection->default_value
        @endphp
        <div class="checkbox">
            <lable for="collection_{{$collection->id}}">
                <input type="hidden" name="collection[{{$collection->id}}]" value="0"/>
                <input type="checkbox" name="collection[{{$collection->id}}]" value="1"
                       id="collection[{{$collection->id}}]" {{$val ? 'checked' : ''}}/>
            </lable>
        </div>
    @endif
@elseif($collection->field_type === 'radio')
    @php
        $val = $collection->answer_value !== '' ? $collection->answer_value : $collection->default_value
    @endphp
    @if($collection->hasList)
        <input type="hidden" name="collection[{{$collection->id}}]" value="0">
        @foreach($collection->hasList as $key => $value)
            <div class="radio">
                <label for="collection_{{$key}}_{{$collection->id}}">
                    <input type="radio" name="collection[{{$collection->id}}]" id="collection_{{$key}}_{{$collection->id}} {{$val == $key ? 'checked' : ''}}">
                </label>
            </div>
        @endforeach
    @else
        <div class="radio">
            <label for="collection_{{$collection->id}}">
                <input type="hidden" name="collection[{{$collection->id}}]" value="0"/>
                <input type="radio" name="collection[{{$collection->id}}]" value="1"
                       id="collection_{{$collection->id}}" {{$val ? 'checked' : ''}}/>
            </label>
        </div>
    @endif
@elseif($collection->field_type === 'date')
    <input id="collection[{{$collection->id}}]" value="{{$value}}" class="form-control" readonly data-selector/>
@elseif($collection->field_type === 'datetime')
    <input id="collection[{{$collection->id}}]" value="{{$value}}" class="form-control" readonly data-time-selector/>
@elseif($collection->field_type === 'dropdown')
    @foreach($collection->hasList as $extra)
        <select name="collection[{{$collection->id}}]" id="collection[{{$collection->id}}]" value="{{$value}}">
            <option value="{{$extra}}">{{$extra}}</option>
        </select>
    @endforeach
@elseif($collection->field_type === 'multi')
    {{--TO DO haslist--}}
    @foreach($collection->hasList as $extra)
        <select name="collection[{{$collection->id}}][]" id="collection[{{$collection->id}}][]" value="{{$values}}" multiple
                class="multiselect form-control" size="10">
            <option value="{{$extra}}">{{$extra}}</option>

        </select>
    @endforeach
@elseif($collection->field_type === 'textarea')
    <textarea name="collection[{{$collection->id}}]" id="collection[{{$collection->id}}]" class="form-control" rows="5">
    </textarea>
@elseif($collection->field_type === 'yesno')
    <div class="checkbox">
        <label for="collection_yes_{{$collection->id}}" class="checkbox-inline">
            <input type="radio" name="collection[{{$collection->id}}]" value="1"
                   id="collection_yes_{{$collection->id}}" {{$value ? 'checked="checked"' : ''}}>
        </label>
        <label for="collection_no_{{$collection->id}}" class="checkbox-inline">
            <input type="radio" name="collection[{{$collection->id}}]" value="0"
                   id="collection_no_{{$collection->id}}" {{$value ? '' : 'checked="checked"'}}>
        </label>
    </div>
@else
    <input type="text" name="collection[{{$collection->id}}]" id="collection[{{$collection->id}}]" class="form-control">
@endif