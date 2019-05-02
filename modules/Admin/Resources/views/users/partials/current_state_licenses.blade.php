
<table class="table table-condensed">
    <tr>
        <th>State</th>
        <th>Number</th>
        <th>Expiration</th>
        <th>Document</th>
    </tr>

    @if($appraiserStateLicenses)
    @foreach($appraiserStateLicenses as $row)
    <tr class="license_tr_row" id="license_tr_row_{{ $row->id }}">
        <td>{{ $row->state }}</td>
        <td>{{ $row->cert_num }}</td>
        <td>{{ $row->cert_expire }}</td>
        @php $document = $service->getUserStateDocument($user->id, $row->state);
        $link = $service->getUserDocumentLink($document);
        @endphp
        <td>
            @if($document && $link)
            <a href='{{ $link }}'  target='_blank'>{{ $row->state }} Document</a> <small>({{ date('m/d/Y H:i', $document->created_date) }})</small>
            @else
            Document Not Found
            @endif
        </td>
    </tr>


    <tr style="display:none;">&nbsp;</tr>

    <tr class="counties_tr_row" id="counties_tr_row_{{ $row->id }}" style="display:none;">
        <td colspan="4" class="state_license_county_title">

            (<small><a href='javascript:void(0);' class='check_all_in_state' id='check_all_in_state_{{ $row->id }}'>Check All</a></small> |
            <small><a href='javascript:void(0);' class='uncheck_all_in_state' id='uncheck_all_in_state_{{ $row->id }}'>Uncheck All</a></small> |
            <small><a href='javascript:void(0);' class='edit_state_license' id='edit_state_license_{{ $row->id }}'>Edit</a></small>
            @if(admin())
            |<small><a href='javascript:void(0);' style="color:red;" class='delete_state_license' id='delete_state_license_{{ $row->id }}'>Delete</a></small>
            @endif



            <div class="row">

                <div class="span7 county_zip_div" id="state_license_edit_table_{{ $row->id }}" style="display:none;">
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">License Number</label>
                        {!! Form::text('state_license_info[' . $row->id . '][number]', $row->cert_num, ['class' => 'form-control', 'size' => 20]) !!}
                    </div>
                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">License Expires</label>
                        {!! Form::text('state_license_info['.$row->id.'][expire]', $row->cert_expire, ['class' => 'datepicker form-control', 'size' => 20]) !!}
                    </div>

                    <div class="control-group" style="margin-bottom: 0px;">
                        <label class="control-label">License Document</label>
                        {!! Form::file('state_license_info['.$row->id.'][file]', ['class' => 'form-control', 'size' => 20]) !!}
                    </div>
                </div>

                <div class='span9 license-state-counties license-state-{{ $row->state }}'>
                    <button type="button" class="btn btn-success btn-mini load-state-license-counties" data-state="{{ $row->state }}" value="Load Counties">Load Counties</button>
                </div>
            </div>
        </td>
    </tr>

    @endforeach
    @else
    <tr>
        <td colspan='4'>No Rows Found.</td>
    </tr>
    @endif

</table>