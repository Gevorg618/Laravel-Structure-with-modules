@foreach($counties->chunk(4) as $items)
    <div class="row">
        @foreach($items as $county)
            <div class="col-md-3">
                <div class="row">
                    <div class="col-md-4">{{$county}}</div>
                    <div class="col-md-4">{{Form::text('county['.$county.']', (isset($taxes) && isset($taxes[$county]))? $taxes[$county] : null, ['class' => 'county form-control'])}}</div>
                    <div class="col-md-2 row">%</div>
                </div>
            </div>
        @endforeach
        <p>&nbsp;</p>
    </div>
@endforeach
<div class="row">
    <div class="ibox-footer">
        <button class="btn btn-success pull-right">{{ isset($state) ? 'Update' : 'Save' }}</button>
    </div>
</div>