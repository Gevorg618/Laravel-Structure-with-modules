<form method="POST" action="{{route('admin.integrations.update-loan-reason')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="loan_reasons_table">
        <caption class="caption">Match Loan Reason</caption>
        <thead>
            <tr>
                <th>Mercury Loan Reason Title</th>
                <th>Internal Loan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mercuryLoanReason as $mercuryReason)
                <tr>

                    <td>{{$mercuryReason->title}}</td>

                    <td>
                        <select class='form-control input-sm' id="reason_{{$mercuryReason->id}}" name="reason[{{$mercuryReason->id}}]">
                            <option value="">--</option>
                            @foreach($internalReason as $types)
                                <option value="{{$types->id}}"
                                    @if(!$savedLoanReasons->where('mercury_type_id', $mercuryReason->id)->where('lni_type_id', $types->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$types->descrip}}
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
