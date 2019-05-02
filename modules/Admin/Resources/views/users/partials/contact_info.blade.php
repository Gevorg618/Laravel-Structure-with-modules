<div class="row">
    <div class="span8">
        <h2>Preferred Communication Method(s)</h2>
        <div class="row language-div">
            @foreach($communicationMethods as $key => $value)
            <div class="span12">
                @php
                $options = [];
                if(in_array($key, $selectedCommunication->toArray())) {
                    $options['checked'] = 'checked';
                }
                @endphp
                {!! Form::checkbox('communicationMethods['.$key.']', $key, null, $options) !!}
                <label for="communicationMethods_{{ $key }}" class="control-label">{{ $value }}</label>
            </div>
            @endforeach
        </div>
    </div>
</div>


<div class="row">
    <div class="span8">
        <h2>Business Hours</h2>
        <div class="row">
            @foreach($businessDays as $day => $dayTitle)
            <div class="span12">
                <div class="control-group" style="margin-bottom: 0px;">
                    <label>{{ $dayTitle }}</label>
                    {!! Form::select('businessHours['.$day.'][from]', $businessHours, isset($selectedHours[$day]['from']) ? $selectedHours[$day]['from'] : null, ['class' => 'form-control', 'placeholder' => 'From']) !!}
                    {!! Form::select('businessHours['.$day.'][to]', $businessHours, isset($selectedHours[$day]['to']) ? $selectedHours[$day]['to'] : null, ['class' => 'form-control', 'placeholder' => 'To']) !!}
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>


