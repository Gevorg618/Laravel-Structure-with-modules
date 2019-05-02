<form method="POST" action="{{route('admin.integrations.update-statuses')}}">
    {{ csrf_field() }}
    <table class="table table-striped table-bordered table-hover" id="statuses_table">
        <caption class="caption">Match statuses</caption>
        <thead>
            <tr>
                <th>Mercury Status ID</th>
                <th>Mercury Status Title</th>
                <th>Internal Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mercuryStatuses as $mercuryStatus)
                <tr>

                    <td>{{$mercuryStatus->external_id}}</td>

                    <td>{{$mercuryStatus->title}}</td>

                    <td>
                        <select class='form-control input-sm' id="status_{{$mercuryStatus->external_id}}" name="status[{{$mercuryStatus->external_id}}]">
                            <option value="">--</option>
                            @foreach($statuses as $status)
                                <option value="{{$status->id}}"
                                    @if(!$saveStatuses->where('mercury_status_id', $mercuryStatus->external_id)->where('lni_status_id', $status->id)->isEmpty())
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
