<form method="POST" action="{{route('admin.integrations.update-loan-type')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="loan_types_table">
        <caption class="caption">Match Loan Types</caption>
        <thead>
            <tr>
                <th>Mercury Loan Type Title</th>
                <th>Internal Loan Type</th>
                <th>Internal Loan Reason</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mercuryLoanTypes as $mercuryLoanType)
                <tr>

                    <td>{{$mercuryLoanType->title}}</td>

                    <td>
                        <select class='form-control input-sm' id="type_{{$mercuryLoanType->id}}" name="type[{{$mercuryLoanType->id}}]">
                            <option value="">--</option>
                            @foreach($internalTypes as $type)
                                <option value="{{$type->id}}"
                                    @if(!$savedTypes->where('mercury_type_id', $mercuryLoanType->id)->where('lni_type_id', $type->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$type->descrip}}
                                </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class='form-control input-sm' id="reason_{{$mercuryLoanType->id}}" name="reason[{{$mercuryLoanType->id}}]">
                            <option value="">--</option>
                            @foreach($internalReason as $type)
                                <option value="{{$type->id}}"
                                    @if(!$savedTypes->where('mercury_type_id', $mercuryLoanType->id)->where('lni_reason_id', $type->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$type->descrip}}
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
