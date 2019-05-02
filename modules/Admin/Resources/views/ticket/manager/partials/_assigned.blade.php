@if (!empty($addLink))
    @php
        $value = $row['assignid']
            ? (
                ($row['assigntype'] == config('constants.assign_type_user'))
                    ? 'user_' . $row['assignid']
                    : 'team_' . $row['assignid']
            ) : '';
    @endphp

    <a href="javascript:;" class="inline-assign-edit"
       data-pk="{{ $row->id }}" data-value="{{ $value }}" data-assign="{{ $value }}" id="assign_{{ $row->id }}">
        @endif

        @if (isset($assignTitle) && $assignTitle)
            {{ $assignTitle }}
        @else
            <i>{{ config('constants.not_available') }}</i>
        @endif

        @if (!empty($addLink))
    </a>
@endif