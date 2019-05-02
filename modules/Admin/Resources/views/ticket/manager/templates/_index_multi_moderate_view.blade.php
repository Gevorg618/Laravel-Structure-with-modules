<div class="row">
    <div class="col-md-12">
        <h1>The following will be applied to {{ count($checked) }} Ticket(s)</h1>
    </div>
    <input type="hidden" id="multi_moderation_id" name="multi_moderation_id" value="{{ $mod->id }}">
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>Open / Close</th>
                    <td>{{ $mod->close_or_open ? ucwords($mod->close_or_open) : '--' }}</td>
                </tr>
                <tr>
                    <th>Assign</th>
                    <td>
                        @if ($mod->assign_to)
                            @if ($mod->assign_to == config('constants.remove_option'))
                                Unset Current
                            @else
                                {{ $assignTitle }}
                            @endif
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @if ($mod->set_status)
                            @if ($mod->set_status == config('constants.remove_option'))
                                Unset Current
                            @else
                                {{ $mod->statusName }}
                            @endif
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td>
                        @if ($mod->set_category)
                            @if ($mod->set_category == config('constants.remove_option'))
                                Unset Current
                            @else
                                {{ $mod->categoryName }}
                            @endif
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Priority</th>
                    <td>
                        @if ($mod->set_priority)
                            @if ($mod->set_priority == config('constants.remove_option'))
                                Unset Current
                            @else
                                {{ $priorityTitle }}
                            @endif
                        @else
                            --
                        @endif
                    </td>
                </tr>
            </table>
        </div>
    </div>
    <div class="col-md-6">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>Assign Order</th>
                    <td>{{ $mod->assign_order ? $mod->assign_order : '--' }}</td>
                </tr>
                <tr>
                    <th>Participants</th>
                    <td>
                        @if ($participants)
                            @foreach ($participants as $participant)
                                {{ $participant }}<br>
                            @endforeach
                        @else
                            --
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Public Comment</th>
                    <td>{{ $mod->public_comment ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Reply</th>
                    <td>{{ $mod->reply ? 'Yes' : 'No' }}</td>
                </tr>
                <tr>
                    <th>Reply All</th>
                    <td>{{ $mod->reply_all ? 'Yes' : 'No' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="col-md-12">
        <b>Comment</b>
        <blockquote>{!! $mod->comment !!}</blockquote>
    </div>
</div>