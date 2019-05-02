 {{ Form::model($pricing, ['class' => 'form-group', 'id' => 'pricing_new_form', 'enctype' => 'multipart/form-data'])}}
    <input type="hidden" id="pricing_id" value="{{ $pricing->id }}">          
    <div class="form-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group col-md-12">
                    <label name="name" class="control-label col-lg-3 col-xs-12">Title
                        <span class="required" aria-required="true"></span>
                    </label>
                    <div class="col-lg-12 col-xs-12">
                        {{ Form::text('title', null, ['class' => 'form-control', 'id' => 'title']) }}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label name="name" class="control-label col-lg-3 col-xs-12"> Position
                    </label>
                    <div class="col-lg-12 col-xs-12">
                        {{ Form::number('pos', null, ['class' => 'form-control', 'id' => 'pos']) }}
                    </div>
                </div>
                <div class="form-group col-md-6">
                    <label name="loan_reason" class="control-label col-lg-6 col-xs-12">Loan Reason
                    </label>
                    <div class="col-lg-12 col-xs-12">
                        {{ Form::select('loan_reason', $loanReasons, $pricing->loanReasons->pluck('id')->toArray(),  ['id' => 'loan_reason', 'class' => 'form-control selectpicker', 'multiple' => 'multiple']) }}
                        <span class="help-block loan_reason-error-block"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
{{ Form::close() }}