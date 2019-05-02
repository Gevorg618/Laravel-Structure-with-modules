{{-- View Ticket--}}
@extends('admin::layouts.master')
@section('title', 'Ticket Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Ticket', 'url' => '#'],
        ['title' => 'Manager', 'url' => route('admin.ticket.manager')]
    ]
])
@endcomponent

@php $order = $ticket->apprOrder; @endphp

@section('content')
    <div class="row">
        <div class="col-md-3">

            <dl>
                <dt>
                    <a class="btn btn-sm btn-primary"
                       href="{{ route('admin.ticket.manager', ['lock_ticket' => $ticket->id, $request->queryString]) }}"><<
                        Back</a>
                </dt>
            </dl>

            <dl>
                <dt>ID</dt>
                <dd>{{ $ticket->id }}</dd>
            </dl>
            <dl>
                <dt>Created Date</dt>
                <dd>{{ date('m/d/Y g:i A', $ticket->created_date) }}</dd>
            </dl>

            @if ($ticket->closed_date)
                <dl>
                    <dt>Closed Date</dt>
                    <dd><span class="label label-success">{{ date('m/d/Y g:i A', $ticket->closed_date) }}</span></dd>
                </dl>
                <dl>
                    <dt>Closed By</dt>
                    <dd>{{ $ticket->closedBy }}</dd>
                </dl>
            @else
                <dl>
                    <dt>Time Open</dt>
                    <dd>
				  <span data-time="{{ date('Y-m-d H:i:s', $ticket->created_date) }}"
                        class="time-show-since label">{{ date('Y-m-d H:i:s', $ticket->created_date) }}</span>
                    </dd>
                </dl>
            @endif

            <dl>
                <dt>From Address</dt>
                <dd>
                    @if ($ticket->from_content)
                        {{ str_replace(',', '<br>', $ticket->from_content) }}
                    @else
                        <i>{{ config('constants.not_available') }}</i>
                    @endif
                </dd>
            </dl>

            @if ($fromUsers)
                <dl>
                    <dt>Matched User by Emails</dt>
                    <dd></dd>
                </dl>

                @foreach ($fromUsers as $row)
                    @if ($row)
                        <dl>
                            <dt>{{ $row->email }}</dt>
                            <dd>{{ $row->fullname }}</dd>
                        </dl>
                    @endif
                @endforeach
            @endif

            @if ($ticket->userid)
                <dl>
                    <dt>Created By</dt>
                    <dd>{{ $ticket->createdBy }}</dd>
                </dl>
            @endif

            <dl>
                <dt>To Address</dt>
                <dd>
                    @if ($ticket->to_content)
                        {{ str_replace(',', '<br>', $ticket->to_content) }}
                    @else
                        <i>{{ config('constants.not_available') }}</i>
                    @endif
                </dd>
            </dl>
            <dl>
                <dt>CC Addresses</dt>
                <dd>
                    @if ($ticket->cc_content)
                        {{ str_replace(',', '<br>', $ticket->cc_content) }}
                    @else
                        <i>{{ config('constants.not_available') }}</i>
                    @endif
                </dd>
            </dl>

            {{-- Order Details --}}
            @if ($ticket->orderid)
                <dl>
                    <dt></dt>
                    <dt>
                        <hr>
                    </dt>
                </dl>

                <dl>
                    <dt>Order ID</dt>
                    <dd>
                        @include('admin::ticket.manager.partials._order_line', ['row' => $ticket])
                    </dd>
                </dl>

                @if (!$ticket->type || $ticket->type == config('constants.order_type_appraisal'))
                    <dl>
                        <dt>Timezone</dt>
                        <dd>{{ getRegionByState($order->propstate) }}</dd>
                    </dl>
                    <dl>
                        <dt>Order Status</dt>
                        <dd>{{ $ticket->orderStatus }}</dd>
                    </dl>
                    <dl>
                        <dt>Client Name</dt>
                        <dd>{{ $order->orderedByUser }}</dd>
                    </dl>
                    <dl>
                        <dt>Vendor Name</dt>
                        <dd>{{ $order->vendorName }}</dd>
                    </dl>
                    <dl>
                        <dt>Borrower Name</dt>
                        <dd>{{ ucwords(strtolower($order->borrower)) }}</dd>
                    </dl>
                    <dl>
                        <dt>Order Address</dt>
                        <dd>{{ $ticket->ticketOrderAddress }}</dd>
                    </dl>
                @endif
            @endif

            <dl>
                <dt></dt>
                <dt>
                    <hr>
                </dt>
            </dl>

            <dl>
                <dt>Assigned</dt>
                <dd>
                    @include('admin::ticket.manager.partials._assigned')
                </dd>
            </dl>

            <dl>
                <dt>Participants</dt>
                <dd>@include('admin::ticket.manager.templates._participants')</dd>
            </dl>

            <dl>
                <dt>Status</dt>
                <dd>@include('admin::ticket.manager.partials._ticket_status', ['row' => $ticket])</dd>
            </dl>

            <dl>
                <dt>Category</dt>
                <dd>@include('admin::ticket.manager.partials._ticket_category')</dd>
            </dl>

            <dl>
                <dt></dt>
                <dt>
                    <hr/>
                </dt>
            </dl>

            <dl>
                <dt>Currently Viewing</dt>
                <div id="currently_viewing_div">
                    @include('admin::ticket.manager.templates._currently_viewing')
                </div>
            </dl>

            <dl>
                <dt>Last Viewed</dt>
                <div id="last_viewed_div">
                    @include('admin::ticket.manager.templates._last_viewed')
                </div>

            </dl>

        </div>
        <div class="col-md-9">
            <h1>@include('admin::ticket.manager.partials._subject_line', ['row' => $ticket])</h1>

            <div class="row">
                <div class="col-md-12">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" id="myTab">
                        <li><a href="#tickets" data-toggle="tab">Details</a></li>
                        <li><a href="#related" data-toggle="tab">Related {{ count($relatedTickets) }}</a></li>
                        <li><a href="#documents" data-toggle="tab">Documents ({{ count($files) }})</a></li>
                        <li><a href="#activity" data-toggle="tab">Activity ({{ count($activity) }})</a></li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div class="tab-pane" id="tickets">
                            <br>
                            @include('admin::ticket.manager.templates._ticket_view_details', ['ticket' => $ticket])


                            <div class="row">
                                <div class="col-md-12">
                                    @include('admin::ticket.manager.templates._ticket_reply')
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12" id="ticket_comments_container">
                                    @include('admin::ticket.manager.templates._ticket_comments')
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane" id="related">
                            @include('admin::ticket.manager.templates._ticket_view_related')
                        </div>

                        <div class="tab-pane" id="documents">
                            @include('admin::ticket.manager.templates._ticket_view_documents')
                        </div>

                        <div class="tab-pane" id="activity">
                            @include('admin::ticket.manager.templates._ticket_view_activity')
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/moment-timezone-with-data.js') }}"></script>
    <script src="{{ masset('js/plugins/pnotify/jquery.pnotify.min.js') }}"></script>
    <script src="{{ masset('js/plugins/idle-timer/idle-timer.min.js') }}"></script>
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/adapters/jquery.js') }}"></script>
@endpush

@push('heads')
    <link rel="stylesheet" href="{{ masset('js/plugins/pnotify/jquery.pnotify.default.css') }}" type="text/css">
@endpush

<div class="modal fade" id="order_modal_idle" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="order_modal_idle_title"></h4>
            </div>
            <div class="modal-body" id="order_modal_idle_content">
                You have been inactive for a while. Click the "I'm Back" button once you are ready to continue browsing
                the ticket screen.
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="order_modal_idle_back">I'm Back</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="comment_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="comment_modal_title"></h4>
            </div>
            <div class="modal-body" id="comment_modal_content"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@php
    $viewingJS = [];
    if ($viewing && count($viewing)) {
         foreach ($viewing as $v) {
            $viewingJS[] = $v->user_id;
         }
    }
@endphp

@push('scripts')
    <script>
      var $ticketId = '{{ intval($ticket->id) }}';

      // Auto Update Currently Viewing
      var $autoCheckCurrentlyViewing = true;
      var $autoCheckCurrentlyViewingInterval = 10000;
      var $currentlyViewing = [{{ implode(',', $viewingJS) }}];
      var $emailTemplate = '{!! $emailTemplate !!}';
      var $savedContent = null;
      var $startDate = '{{ time() }}';
      $().ready(function () {
        registerCKeditorInstance();
      });
    </script>

    <script src="{{ masset('js/ticket/ticket.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-manager-main.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-manager-view.js') }}"></script>
@endpush
