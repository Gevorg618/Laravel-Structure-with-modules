@if($row->is_processing)
    <div class="alert alert-danger">
        The last submission is currently being processed. Please wait...<br />
        Submitted By {{getUserFullNameById($row->created_by)}} On {{date('m/d/Y g:i A', $row->created_date)}}
    </div>
@elseif($row->is_error)
    <div class="alert alert-danger">
        The last submission attempt resulted in an error. The following error was returned: {{$row->error}} We have attempted to submit at least {{$row->attempts}} times.<br />
        Submitted By {{echo getUserFullNameById($row->created_by)}} On {{date('m/d/Y g:i A', $row->created_date)}}
    </div>
@elseif($row->is_completed)
    <div class="alert alert-success">
        The last submission was completed succesfully.<br />
        Submitted By {{getUserFullNameById($row->created_by)}} On {{date('m/d/Y g:i A', $row->created_date)}}
    </div>
@else
    <div class="alert alert-danger">
        There is currently a submission pending. Please wait until this submission is processed.<br />
        Submitted By {{getUserFullNameById($row->created_by)}} On {{date('m/d/Y g:i A', $row->created_date)}}
        <?php if($row->error): ?>
            <br /><br />The following error was returned: {{$row->error}} <br />We have attempted to submit at least {{$row->attempts}} times.<br />
        <?php endif; ?>
    </div>
@endif