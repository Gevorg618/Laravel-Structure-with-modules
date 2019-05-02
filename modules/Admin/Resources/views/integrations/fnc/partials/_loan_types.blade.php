<form method="POST" action="{{route('admin.integrations.update-fnc-loan-type')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="appraisal_types_table">
        <caption class="caption">Match Loan Types</caption>
        <thead>
            <tr>
                <th>FNC Loan Type Title</th>
                <th>Internal Loan Type</th>
                <th>Internal Loan Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fncLoanTypes as $fncLoanType)
                <tr>

                    <td>{{$fncLoanType->value}}</td>

                    <td>
                        <select class='form-control input-sm' name="type[{{$fncLoanType->key}}]">
                            <option value="">--</option>
                            @foreach($loanTypes as $loanType)
                                <option value="{{$loanType->id}}"
                                    @if(!$savedTypes->where('fnc_type_id', $fncLoanType->key)->where('lni_type_id', $loanType->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$loanType->descrip}}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class='form-control input-sm' name="reason[{{$fncLoanType->key}}]">
                            <option value="">--</option>
                            @foreach($loanReason as $reason)
                                <option value="{{$reason->id}}"
                                    @if(!$savedTypes->where('fnc_type_id', $fncLoanType->key)->where('lni_reason_id', $reason->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$reason->descrip}}
                                </option>
                            @endforeach
                        </select>
                    </td>

                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="buttons_content">
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button class="btn btn-primary">Submit</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>