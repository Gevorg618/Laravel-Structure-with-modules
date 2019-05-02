<div class="form-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group col-md-6">
                <label name="descrip" class="control-label col-lg-3 col-xs-12">Title
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('descrip', null, ['class' => 'form-control']) !!}
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="baseprice_con" class="control-label col-lg-12 col-xs-12">Base Price Conventional
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('baseprice_con', null, ['class' => 'form-control']) !!}
                    <span class="help-block baseprice_con-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="fha" class="control-label col-lg-3 col-xs-12"> FHA
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('fha', ['N' => 'No', 'Y' => 'Yes'], null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="baseprice_fha" class="control-label col-lg-12 col-xs-12">Base Price FHA
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('baseprice_fha', null, ['class' => 'form-control']) !!}
                    <span class="help-block baseprice_fha-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="short_descrip" class="control-label col-lg-12 col-xs-12">Short Description
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('short_descrip', null, ['class' => 'form-control']) !!}
                    <span class="help-block short_descrip-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="active" class="control-label col-lg-12 col-xs-12">Active
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('active', ['N' => 'No', 'Y' => 'Yes'], null, ['class' => 'form-control']) }}
                    <span class="help-block active-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="form" class="control-label col-lg-12 col-xs-12">Form
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('form', null, ['class' => 'form-control']) !!}
                    <span class="help-block form-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="require_xml" class="control-label col-lg-12 col-xs-12">Require XML
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('require_xml', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control']) }}
                    <span class="help-block require_xml-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="realview_type" class="control-label col-lg-12 col-xs-12">RealView Type
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('realview_type', null, ['class' => 'form-control']) !!}
                    <span class="help-block realview_type-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="require_pdf" class="control-label col-lg-12 col-xs-12">Require PDF
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('require_pdf', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control']) }}
                    <span class="help-block require_pdf-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="ead_form" class="control-label col-lg-12 col-xs-12">EAD Form
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('ead_form', null, ['class' => 'form-control']) !!}
                    <span class="help-block ead_form-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="position" class="control-label col-lg-12 col-xs-12">Position
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('position', null, ['class' => 'form-control']) !!}
                    <span class="help-block position-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="mismo_label" class="control-label col-lg-12 col-xs-12">Mismo Label
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::text('mismo_label', null, ['class' => 'form-control']) !!}
                    <span class="help-block mismo_label-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="is_allowed_license_bypass" class="control-label col-lg-12 col-xs-12">Bypass License Check
                <a href="#" data-toggle="tooltip" title="If turned on, For appraisers who have this setting enabled as well, Those appraisers will be able to accept work in states they do not have ASC/FHA licenses. Only for this appraisal  type and only for appraisers who have this setting On in their profile.">?</a> 
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::select('is_allowed_license_bypass', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control']) !!}
                    <span class="help-block is_allowed_license_bypass-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="order_placement_comments" class="control-label col-lg-12 col-xs-12">Order Placement Comments
                </label>
                <div class="col-lg-12 col-xs-12">
                    {!! Form::textarea('order_placement_comments', null, ['class' => 'form-control']) !!}
                    <span class="help-block order_placement_comments-error-block"></span>
                </div>
            </div>
        </div>
        
    </div>
    <div class="form-group col-md-12">
        <div class="col-lg-6 col-xs-12">
            <button type="submit" class="btn btn-primary">{{ $button_label }}</button>          
        </div>
    </div>
</div>