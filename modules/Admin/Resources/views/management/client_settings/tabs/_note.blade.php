@push('style')
    <style>
        .borderless tr, .borderless td, .borderless th {
            border: none !important;
        }

        .logs_table_overflow {
            max-height: 600px;
            overflow-y: auto;
        }
    </style>
@endpush
<div class="col-md-10">
    <div id="userdrounote">
        <div class="logs_table_overflow">
            <h2>Total Notes {{$notes ? count($notes) : 0}}</h2>
            <table class="table table-bordered">
                <thead class="borderless">
                <tr>
                    <th>Date</th>
                    <th>By</th>
                    <th>Note</th>
                </tr>
                </thead>
                <tbody class="borderless">
                @if($notes && count($notes))
                    @foreach($notes as $note)
                        <tr>
                            <td>{{date('m/d/Y H:i', strtotime($note->dts))}}</td>
                            <td>{{$note->adminid ? getUserFullNameById($note->adminid) : 'N/A'}}</td>
                            <td>{{$note->notes}}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td>No Notes Found.</td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
    <h2>Add Note</h2>
    <div class="form-group row">
        <div class="col-md-7">
        <textarea rows="3" name="user_note" cols="20" id="user_note"
                  class="form-control"></textarea>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-md-7">
            <button type="submit"  id="add_note" class="btn btn-success">Add Note</button>
        </div>
    </div>
    <input type="hidden" name="groupId" value="{{$client->id}}" id="group_id">
</div>


