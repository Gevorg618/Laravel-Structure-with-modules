<div class="form-body">
    <div class="row">
        <div class="col-lg-12">

            <div class="form-group col-md-6">
                <label name="file_name" class="control-label col-lg-3 col-xs-12">File Name
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('file_name', null, ['class' => 'form-control']) !!}
                    <span class="help-block file_name-error-block"></span>
                </div>
            </div>                   
            
            <div class="form-group col-md-6">
                <label name="file_location" class="control-label col-lg-3 col-xs-12">File Location 
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    <label class="btn btn-default">
                         <input type="file" hidden name="file_location">
                    </label>
                    <span class="help-block file_location-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label name="state" class="control-label col-lg-6"> Active
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-sm-8">
                    <label class="radio-inline">                         
                        {!! Form::radio('is_active', '0', true, ['id' => 'radio1']) !!} Yes
                    </label>
                    <label class="radio-inline"> 
                        {!! Form::radio('is_active', '1', false, ['id' => 'radio1']) !!} No
                    </label>
                </div>
            </div>
            <div class="form-group col-md-4">
                <label name="state" class="control-label col-lg-6"> Client Visible
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-sm-8">
                    <label class="radio-inline">                         
                        {!! Form::radio('is_client_visible', '0', true, ['id' => 'radio1']) !!} Yes
                    </label>
                    <label class="radio-inline"> 
                        {!! Form::radio('is_client_visible', '1', false, ['id' => 'radio1']) !!} No
                    </label>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="state" class="control-label col-lg-6"> Appraiser Visible
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-sm-8">
                    <label class="radio-inline">                         
                        {!! Form::radio('is_appr_visible', '0', true, ['id' => 'radio1']) !!} Yes
                    </label>
                    <label class="radio-inline"> 
                        {!! Form::radio('is_appr_visible', '1', false, ['id' => 'radio1']) !!} No
                    </label>
                </div>
            </div>
            <div class="ibox">
        </div>
            <div class="form-group col-md-4">
                <label name="lenders" class="control-label col-lg-6"> Lenders                   
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('lenders[]', $lesaleLenders, isset($document)  ?  $document->lenderPivot->pluck('id'): null, ['class' => 'form-control selectpicker',
                        'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true' ]) 
                    }}
                    <span class="help-block lenders-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="clients" class="control-label col-lg-6"> Clients
                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('clients[]', $clients, isset($document)  ? $document->clientPivot->pluck('id'): null, ['class' => 'form-control selectpicker',
                     'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block clients-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="states" class="control-label col-lg-6"> States
                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('states[]', $states, isset($document)  ? $document->statePivot->pluck('abbr') : null, ['class' => 'form-control selectpicker',
                     'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block states-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="locations" class="control-label col-lg-6">Locations</label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('locations[]', $locations, isset($document)  ? $document->locationPivot->pluck('id') : null , ['class' => 'form-control selectpicker', 
                                    'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block locations-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="types" class="control-label col-lg-6"> Appraisal Types
                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('types[]', $types, isset($document)  ? $document->apprTypePivot->pluck('id'): null, ['class' => 'form-control selectpicker',
                         'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block types-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="state" class="control-label col-lg-6"> Loan Types                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('loan_types[]', $loanTypes, isset($document)  ? $document->loanTypePivot->pluck('id') : null, ['class' => 'form-control selectpicker', 'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block loan_types-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="loan_reasons" class="control-label col-lg-6"> Loan Reason                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('loan_reasons[]', $loanReasons, isset($document)  ? $document->loanReasonPivot->pluck('id') : null, ['class' => 'form-control selectpicker', 'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block loan_reasons-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="property_types" class="control-label col-lg-6"> Property Type                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('property_types[]', $propertyTypes, isset($document)  ? $document->loanPropertyPivot->pluck('id') : null, ['class' => 'form-control selectpicker', 'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block property_types-error-block"></span>
                </div>
            </div>

            <div class="form-group col-md-4">
                <label name="occupancy_statuses" class="control-label col-lg-6"> Occupancy Status                    
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('occupancy_statuses[]', $occupancyStatuses, isset($document)  ? $document->occStatusPivot->pluck('id') : null, ['class' => 'form-control selectpicker', 'multiple' => 'multiple', 'data-show-subtext' => 'true', 'data-live-search' => 'true']) }}
                    <span class="help-block occupancy_statuses-error-block"></span>
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