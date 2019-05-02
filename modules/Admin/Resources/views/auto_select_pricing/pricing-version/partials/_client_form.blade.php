{{ Form::open(	[ 'route' => ['admin.autoselect.pricing.versions.pricing-custom-update', $clientId ], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}         
    <div class="form-body">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="form-group col-md-12">
                    <label name="loan_reason" class="control-label col-lg-3 col-xs-12">Loan Reason
                    </label>
                    <div class="col-lg-12 col-xs-12">
                        {{ Form::select('loan_reason[]', $loanResaonPublic , $loanReasons,  ['id' => 'loan_reason', 'class' => 'form-control selectpicker', 'multiple' => 'multiple']) }}
                        <span class="help-block loan_reason-error-block"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
	    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    <button type="submit" class="btn btn-primary" > Save </button>
	</div>
{{ Form::close() }}