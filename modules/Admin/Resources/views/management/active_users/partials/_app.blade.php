@foreach($userTypes as $type)
    @if(!countActiveUsersByTypeForApp($type->id, $time))
        @continue
    @endif
    <div class="tab_content">
        <h3>{{$type->descrip}}</h3>
        <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Logged In</th>
                        <th>Last Clicked</th>
                        <th>Device</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach(getActiveUsersByTypeForApp($type->id, $time) as $row)
                        <tr>
                            <td>{{$row->userData['firstname']}} {{$row->userData['lastname']}}</td>
                            <td>{{date('m/d/Y H:i:s', $row->created)}}</td>
                            <td>{{date('m/d/Y H:i:s', $row->last_click)}}</td>
                            <td>{{$row->device_name}} {{substr($row->device_version, 0, 5)}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
