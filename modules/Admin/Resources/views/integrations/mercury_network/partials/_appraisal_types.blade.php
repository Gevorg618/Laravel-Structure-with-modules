<form method="POST" action="{{route('admin.integrations.update-appr-types')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="appraisal_types_table">
        <caption class="caption">Match Appraisal Types</caption>
        <thead>
            <tr>
                <th>Mercury</th>
                <th colspan="4">Internal</th>
            </tr>
            <tr>
                <th>Appraisal Title</th>
                <th>Appraisal Type</th>
                <th>Property Type</th>
                <th>Occupancy Status</th>
                <th>Addendas</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mercuryApprTypes as $mercuryApprType)
                <tr>

                    <td>{{$mercuryApprType->title}}</td>

                    <td>
                        <select class='form-control input-sm' id="appr_type{{$mercuryApprType->id}}" name="appr_type[{{$mercuryApprType->id}}]">
                            <option value="">--</option>
                            @foreach($internalApprTypes as $internalApprType)
                                <option value="{{$internalApprType->id}}"
                                    @if(!$savedData->where('mercury_type_id', $mercuryApprType->id)->where('lni_type_id', $internalApprType->id)->isEmpty())
                                        selected
                                    @endif
                                >{{$internalApprType->getTitleAttribute()}}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class='form-control input-sm' id="prop_type{{$mercuryApprType->id}}" name="prop_type[{{$mercuryApprType->id}}]">
                            <option value="">--</option>
                            @foreach($internalPropertyTypes as $internalPropertyType)
                                <option value="{{$internalPropertyType->id}}"
                                    @if(!$savedData->where('mercury_type_id', $mercuryApprType->id)->where('property_type_id', $internalPropertyType->id)->isEmpty())
                                        selected
                                    @endif
                                >{{$internalPropertyType->descrip}}</option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class='form-control input-sm' id="acc_status{{$mercuryApprType->id}}" name="occ_status[{{$mercuryApprType->id}}]">
                            <option value="">--</option>
                            @foreach($internalOccupancyStatuses as $internalOccupancyStatus)
                                <option value="{{$internalOccupancyStatus->id}}"
                                    @if(!$savedData->where('mercury_type_id', $mercuryApprType->id)->where('occ_type_id', $internalOccupancyStatus->id)->isEmpty())
                                        selected
                                    @endif
                                > {{$internalOccupancyStatus->descrip}} </option>
                            @endforeach
                        </select>
                    </td>

                    <td>
                        <select class='form-control input-sm' id="addendas{{$mercuryApprType->id}}" name="addendas[{{$mercuryApprType->id}}][]" multiple>
                            @foreach($internalAddendas as $internalAddenda)
                                {{$addenda = $savedData->where('mercury_type_id', $mercuryApprType->id)->first()['addendas']}}
                                <option value="{{$internalAddenda->id}}"
                                    @if(in_array($internalAddenda->id, explode(',', $addenda)))
                                        selected
                                    @endif
                                >
                                    {{$internalAddenda->descrip}}
                                </option>
                            @endforeach
                        </select>
                    </td>

                </tr>
            @endforeach
            <tr>
                <td colspan="5" class="buttons_content">
                    <button type="reset" class="btn btn-default">Reset</button>
                    <button class="btn btn-primary">Submit</button>
                </td>
            </tr>
        </tbody>
    </table>
</form>
