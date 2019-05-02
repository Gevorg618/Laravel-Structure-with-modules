<form method="POST" action="{{route('admin.integrations.update-fnc-statuses')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="statuses_table">
        <caption class="caption">Match statuses</caption>
        <thead>
            <tr>
                <th>FNC Status ID</th>
                <th>FNC Status Title</th>
                <th>Internal Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($fncStatuses as $fncStatus)
                <tr>

                    <td>{{$fncStatus->key}}</td>

                    <td>{{$fncStatus->value}}</td>

                    <td>
                        <select class='form-control input-sm' name="status[{{$fncStatus->key}}]">
                            <option value="">--</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}"
                                    @if(!$saveStatuses->where('fnc_status_id', $fncStatus->key)->where('lni_status_id', $status->id)->isEmpty())
                                        selected
                                    @endif>
                                    {{$status->descrip}}
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