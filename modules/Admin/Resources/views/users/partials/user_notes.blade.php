<h2>{{ sprintf("Total Notes %s", count($notes)) }}</h2>

<table class="table table-responsive">
    <tr>
        <th>Created Date</th>
        <th>Created By</th>
        <th>Note</th>
        <th>Options</th>
    </tr>

    @if($notes)

    @foreach($notes as $note)
    <tr>
        <td>{{ date('m/d/Y H:i', strtotime($note->dts)) }}</td>
        <td>{{ $note->user->fullname }}</td>
        <td id='node-message-{{ $note->id }}'>
            @if($note->message)
            <a href='javascript:void(0);' class='view-note' id='view-note-{{ $note->id }}'>{{ $note->notes }}</a>
            @else
						{{ $note->notes }}
					@endif
            @if($note->last_edited_by)
            <br /><small>(Last Edited By {{ $note->lastEditorFullName }} On {{ date('m/d/Y G:i A', $note->last_edited_date) }})</small>
            @endif
        </td>
        <td>
            @if(!$note->message && (checkPermission($adminPermissionCategory, 'can_edit_user_notes') && (strtotime($note->dts) >= (time() - (60*60*\App\Models\Tools\Setting::getSetting('user_notes_time_frame')) ) || checkPermission($adminPermissionCategory, 'can_bypass_user_notes_edit_time_frame')) ) )
            <a href='javascript:void(0);' class='edit-user-note' data-id="{{ $note->id }}">Edit</a>
            @endif
        </td>
    </tr>
    @endforeach

    @else
    <tr>
        <td colspan="3">No Notes Found.</td>
    </tr>
    @endif
</table>

<div class="row">
    <div class="span3">
        <h2>Add Note</h2>
        {!! Form::textarea('user_note', null, ['class' => 'form-control ckeditor', 'rows' => 3]) !!}
    </div>
</div>
<br />
<div class="row">
    <div class="span3">
        <button type="button" id="add-note" data-id="{{ $user->id }}" name="add-note" class="btn btn-primary">Add Note</button>
    </div>
</div>

