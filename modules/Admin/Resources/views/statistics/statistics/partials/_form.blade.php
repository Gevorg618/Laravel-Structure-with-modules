<div class="form-group col-md-4">
    <label name="title" class="control-label  col-xs-12">Date From
        
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('date_from', null, ['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']) !!}
        <span class="help-block title-error-block"></span>
    </div>
</div>

<div class="form-group col-md-4">
    <label name="title" class="control-label col-xs-12">Date To
        
    </label>
    <div class="col-lg-12 col-xs-12">
        {!! Form::text('date_to', null, ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date To']) !!}
        <span class="help-block title-error-block"></span>
    </div>
</div>


<div class="form-group col-md-4">
    <label name="clients" class="control-label col-lg-6"> Clients
        
    </label>
    <div class="col-lg-12 col-xs-12">
        {{ Form::select('clients[]', $clients, null, ['class' => 'form-control selectpicker', 'id' => 'clients',
         'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
        <span class="help-block clients-error-block"></span>
    </div>
</div>

<div class="form-group col-md-12">              
    <button type="button" id="show_button" class="btn btn-primary pull-right">{{ $button_label }}</button>   
    <button type="button" style="margin-right: 27px;" id="reset" class="btn btn-danger pull-right">Reset</button>  
</div>