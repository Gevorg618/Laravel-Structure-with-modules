<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-xs btn-default" id="refresh_comments_button">Refresh
                            Comments
                        </button>

                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
                                Visibility <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="javascript:;" class="show-visibility show-all-activity">Show All</a></li>
                                <li><a href="javascript:;" class="show-visibility show-comments">Show Comments</a></li>
                                <li><a href="javascript:;" class="show-visibility show-activity">show Activity</a></li>
                            </ul>
                        </div>
                    </div>

                </div>
                <h3 class="panel-title">Activity <span class="activity_count_title">({{ count($ticketComments) }}
                        )</span></h3>
            </div>

            <div class="panel-body">
                @if ($ticketComments)
                    @foreach ($ticketComments as $row)
                        @include('admin::ticket.manager.partials._ticket_comment', ['row' => $row])
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</div>