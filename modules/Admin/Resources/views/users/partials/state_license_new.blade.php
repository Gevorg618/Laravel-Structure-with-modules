<div class="new_state_license_div">

    <h4 class="text-info">Add State License</h4>
    <div class="control-group" style="margin-bottom: 0px;">
        <label class="control-label">License State</label>
        {!! Form::select('new_license_state', getStates(), null, ['class' => 'form-control', 'placeholder' => 'Choose state']) !!}
    </div>
    <div class="control-group" style="margin-bottom: 0px;">
        <label class="control-label">License Number</label>
        {!! Form::text('new_license_number', null, ['class' => 'form-control', 'size' => 20]) !!}
    </div>
    <div class="control-group" style="margin-bottom: 0px;">
        <label class="control-label">License Expires</label>
        {!! Form::text('new_license_expire', null, ['class' => 'datepicker form-control', 'size' => 20]) !!}
    </div>

    <div class="control-group" style="margin-bottom: 0px;">
        <label class="control-label">License Document</label>
        {!! Form::file('new_license_file', ['class' => 'form-control', 'size' => 20]) !!}
    </div>
    <hr />
    <div class="control-group offset2" style="margin-bottom: 0px;">
        <button type="button" id="add_state_license" name="add_state_license" value="Add License" class="btn btn-mini">Add License</button>
        <span id='loading_new_state' style='display:none;'>Loading...</span>
    </div>

</div>