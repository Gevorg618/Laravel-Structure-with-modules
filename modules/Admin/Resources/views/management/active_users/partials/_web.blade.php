@foreach($userTypes as $type)
    @if(!countActiveUsersByType($type->id, $time))
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
                        <th>IP</th>
                        <th>Browser</th>
                        <th>Location</th>
                        <th>Phone</th>
                        <th>Tablet</th>
                    </tr>
                </thead>
                <tbody>
                        @foreach(getActiveUsersByType($type->id, $time) as $row)
                            <tr>
                                <td>{{$row->userData['firstname']}} {{$row->userData['lastname']}}</td>
                                <td>{{date('m/d/Y H:i:s', $row->created)}}</td>
                                <td>{{date('m/d/Y H:i:s', $row->last_click)}}</td>
                                <td>{{$row->ip_address}}</td>
                                <td>{{$row->browser_name}} {{substr($row->browser_version, 0, 5)}}</td>
                                <td>{{$row->location}}</td>
                                <td class="text_center">
                                    @if($row->is_phone)
                                        <i class="fa fa-circle" aria-hidden="true" title="{{$row->mobile_name}}"></i>
                                    @endif
                                </td>
                                <td class="text_center">
                                    @if($row->is_tablet)
                                        <i class="fa fa-circle" aria-hidden="true" title="{{$row->mobile_name}}"></i>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endforeach
