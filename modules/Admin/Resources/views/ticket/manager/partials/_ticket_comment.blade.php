@php $commentId = $row->comment ? 'id="ticket_comment_item_' . $row->id . '"' : ''; @endphp

<div {!! $commentId !!} class="media media-single-activity {{ $row->message ? 'media-activity' : 'media-comment' }}">

    <div class="pull-left">
        <div class="btn-group">
            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                @if ($row->is_public)
                    <li><a href="javascript:;" class="set-comment-visibility set-comment-private"
                           data-id="{{ $row->id }}">Private</a></li>
                @else
                    <li><a href="javascript:;" class="set-comment-visibility set-comment-public"
                           data-id="{{ $row->id }}">Public</a></li>
                @endif
            </ul>
        </div>
    </div>

    <div class="pull-left">
        <a href="javascript:;" title="{{ $row->user->fullname }}" rel="tooltip">
            <img class="media-object img-rounded"
                 src="https://placeholdit.imgix.net/~text?txt={{ $row->user->initials }}&w=40&h=40&txtpad=1&txtsize=16">
        </a>
    </div>

    <div class="media-body">
        <div class="panel panel-default" style="margin-bottom:0px;">
            <div class="panel-heading">
                {{ ($row->message) ? 'Activity' : 'Comment' }}

                ({{ date('m/d/Y G:i A', $row->created_date) }})

                @if ($row->is_public))
                <a href="javascript:;" rel="tooltip" title="Public Comment"><i class="fa fa-eye"></i></a>
                @endif
            </div>

            <div class="panel-body">
                @if ($row->message)
                    {!! $row->message !!}
                @else
                    @if ($row->html_content)
                        @if ($row->attachments)
                            <i class="fa fa-paperclip"></i>
                        @endif

                        <a href="javascript:;" rel="tooltip" title="View HTML Content" class="view-comment-content"
                           data-id="{{ $row->id }}">{!! $row->comment !!}</a>
                    @else
                        {!! $row->comment !!}
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>