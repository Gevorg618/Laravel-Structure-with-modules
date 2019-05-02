<table width="100%" class="table table-striped table-hover">
    <thead>
    <tr>
        <th>Subject</th>
        <th>From</th>
        <th>Date</th>
        <th>To</th>
    </tr>
    </thead>
    @if ($emails && $emails['messages'])
        @foreach ($emails['messages'] as $message)
            <tr>
                <td><a href="javascript:void(0);" class="view-email-contents"
                       data-id="{{ $message['messageId'] }}">{{ $message['messageSubject'] }}</a>
                </td>
                <td>
                    @if ($message['messageSender'] && isset($message['messageSender'][0]['email']))
                        {{ $message['messageSender'][0]['email'] }}
                    @else
                        --
                    @endif
                </td>
                <td>{{ date('m/d/y g:i A', $message['messageUnixDate']) }}</td>
                <td>
                    @if ($message['messageTo'] && isset($message['messageTo'][0]['email']))
                        {{ $message['messageTo'][0]['email'] }}
                    @else
                        --
                    @endif
                </td>
            </tr>
        @endforeach
    @endif
</table>