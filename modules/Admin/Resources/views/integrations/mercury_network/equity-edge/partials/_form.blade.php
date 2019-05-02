 <div class="form-body">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group col-md-6">
                <label name="name" class="control-label col-lg-3 col-xs-12">Product Name
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::text('product_name', null, ['class' => 'form-control']) }}
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="title" class="control-label col-lg-3 col-xs-12">Appraisal Type
                    <span class="required" aria-required="true"></span>
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('appr_type', $apprTypes, null, ['class' => 'form-control']) }}
                    <span class="help-block title-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="title" class="control-label col-lg-3 col-xs-12">Loan Reason
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('loan_reason', $loanReasons, null, ['class' => 'form-control']) }}
                    <span class="help-block title-error-block"></span>
                </div>
            </div>
            <div class="form-group col-md-6">
                <label name="title" class="control-label col-lg-3 col-xs-12">Loan Type
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('loan_type', $loanTypes, null, ['class' => 'form-control']) }}
                    <span class="help-block title-error-block"></span>
                </div>
            </div>
        </div>
    </div>
    <div class="row" style="margin-top: 50px;">
        <div class="ibox-footer">
            <button type="submit" class="btn btn-success pull-right">Save</button>
        </div>
    </div>
</div>