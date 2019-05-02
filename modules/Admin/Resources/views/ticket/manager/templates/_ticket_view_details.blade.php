<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="pull-right">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary btn-xs dropdown-toggle"
                                data-toggle="dropdown">Quick Moderation <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu" role="menu">
                            @if ($ticket->closed_date)
                                <li class="option">
                                    <a href="javascript:;" class="quick-option-open"
                                       data-id="{{ $ticket->id }}">Open Ticket</a>
                                </li>
                            @else
                                <li class="option"><a href="javascript:;" class="quick-option-close and-next"
                                                      data-id="{{ $ticket->id }}">Close & Next</a></li>

                                <li class="option"><a href="javascript:;" class="quick-option-close"
                                                      data-id="{{ $ticket->id }}">Close Ticket</a></li>
                            @endif
                        </ul>
                    </div>
                </div>
                <h3 class="panel-title">Ticket Content</h3>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-12">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs" id="ticket_content_nav">
                            <li class="active"><a href="#ticket_content_html" data-toggle="tab">HTML</a></li>
                            <li><a href="#ticket_content_text" data-toggle="tab">Text</a></li>
                        </ul>

                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div class="tab-pane active" id="ticket_content_html">
                                <iframe class="container ticket_contents_frame" id="ticket_contents_frame"
                                        frameborder="0"
                                        src="{!! route('admin.ticket.manager.view_ticket_content', ['id' => $ticket->id, 'type' => 'html']) !!}"
                                        style="width:100%;overflow-x:auto;overflow-y:auto;"></iframe>
                            </div>

                            <div class="tab-pane" id="ticket_content_text">
                                <iframe class="container ticket_contents_frame" id="ticket_contents_frame"
                                        frameborder="0"
                                        src="{!! route('admin.ticket.manager.view_ticket_content', ['id' => $ticket->id, 'type' => 'text']) !!}"
                                        style="width:100%;overflow-x:auto;overflow-y:auto;"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>