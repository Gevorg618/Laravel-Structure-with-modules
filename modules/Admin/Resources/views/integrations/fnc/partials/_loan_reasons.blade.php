<form method="POST" action="{{route('admin.integrations.update-fnc-loan-reason')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="appraisal_types_table">
        <caption class="caption">Match Loan Reason</caption>
        <thead>
            <tr>
                <th>FNC Loan Reason Title</th>
                <th>Internal Loan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fncLoanReasons as $fncLoanReason)
                <tr>

                    <td>{{$fncLoanReason->value}}</td>

                    <td>
                        <select class='form-control input-sm' name="reason[{{$fncLoanReason->key}}]">
                            <option value="">--</option>
                            @foreach($internalTypes as $internalType)
                                <option value="{{$internalType->id}}"
                                    @if(!$savedReasons->where('fnc_type_id', $fncLoanReason->key)->where('lni_type_id', $internalType->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$internalType->descrip}}
                                </option>
                            @endforeach
                        </select>
                    </td>

                </tr>
            @endforeach
            <tr>
                <td colspan="2" class="buttons_content">
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button class="btn btn-primary">Submit</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>