<div class="form-body">
    <div class="table-responsive">
        <table class="fixed_headers table table-striped table-bordered table-hover">
            <thead>
                <tr>
                    <th>Appraisal Type</th>
                    <th>Amount</th>
                    <th>FHA Amount</th>
                    <th>Fee Type</th>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                    <td>
                        <div class="col-md-9">
                            {{ Form::number(null, 0, ['class' => 'form-control', 'id' => $state->abbr.'_all_amount', 'step' =>'0.01']) }}
                        </div>
                        <button class="btn btn-small" id="all_amount_button" data-state-abbr="{{ $state->abbr }}" type="button">Set</button>
                    </td>
                    <td>
                        <div class="col-md-9">
                            {{ Form::number(null, 0, ['class' => 'form-control', 'id' => $state->abbr.'_all_fha_amount', 'step' =>'0.01']) }}
                        </div>
                        <button class="btn" id="all_fhaamount_button" data-state-abbr="{{ $state->abbr }}" type="button">Set</button>
                    </td>
                    <td>
                        <div class="col-md-9">
                            {{ Form::select(null, ['' => '-- Select Fee --', 'fixed' => 'Fixes Price', 'deduct' =>'Deduct Amount'], null, ['class' => 'form-control ', 'id' => $state->abbr.'_all_fee_type']) }}
                        </div>
                        <button class="btn" id="all_fee_type_button" data-state-abbr="{{ $state->abbr }}" type="button">Set</button>
                    </td>
                </tr>
            </thead>
            <tbody class="option_select">
                @include('admin::auto_select_pricing.version-fees.partials._form-render')
            </tbody>
        </table>
    </div>
    <div class="row m-t-lg">
        <div class="col-md-12 ">
            {!! Form::submit('Update - '.$state->state, ['class' => 'btn btn-success form-control' , 'id' => 'update_state' , 'state-abbr' => $state->abbr , 'group-id' => $objectId]) !!}
        </div>
    </div>
</div>
