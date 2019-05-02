<form method="POST" action="{{route('admin.integrations.update-fnc-property-types')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="appraisal_types_table">
        <caption class="caption">Match Property Types</caption>
        <thead>
            <tr>
                <th>FNC Property Type Title</th>
                <th>Internal Property Type</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fncPropertyTypes as $fncPropertyType)
                <tr>

                    <td>{{$fncPropertyType->value}}</td>

                    <td>
                        <select class='form-control input-sm' name="property[{{$fncPropertyType->key}}]">
                            <option value="">--</option>
                            @foreach($internalTypes as $internalType)
                                <option value="{{$internalType->id}}"
                                    @if(!$savedTypes->where('fnc_type_id', $fncPropertyType->key)->where('lni_type_id', $internalType->id)->isEmpty())
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