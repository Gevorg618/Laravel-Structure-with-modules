<div class="form-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group col-md-6">
                <label name="state" class="control-label col-lg-3 col-xs-12">State Name
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('state', null, ['class' => 'form-control']) !!}
                    <span class="help-block state-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="abbr" class="control-label col-lg-3 col-xs-12">State Abbr
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('abbr', null, ['class' => 'form-control']) !!}
                    <span class="help-block abbr-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="state_2" class="control-label col-lg-3 col-xs-12">State Adjacent
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('state_2[]', $states, $adjacentStates,
                    ['class' => 'form-control selectpicker', 'multiple' , 'multiple data-selected-text-format="count > 6"']) }}
                    <span class="help-block state_2-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="timezone_id" class="control-label col-lg-3 col-xs-12">Timezones
                    <span class="required" aria-required="true"></span>
                </label>
                {{ Form::select('timezone_id', $timezones, null, ['class' => 'form-control selectpicker']) }}
                <div class="col-lg-12 col-xs-12">
                    <span class="help-block timezone_id-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="region_id" class="control-label col-lg-3 col-xs-12">Regions
                    <span class="required" aria-required="true"></span>
                </label>
                {{ Form::select('region_id', $regions, null, ['class' => 'form-control selectpicker']) }}
                <div class="col-lg-12 col-xs-12">
                    <span class="help-block region_id-title-error-block"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 50px;">
        <div class="ibox-footer">
            <button type="submit" class="btn btn-success pull-left">{{ $button_label }}</button>
        </div>
    </div>
</div>