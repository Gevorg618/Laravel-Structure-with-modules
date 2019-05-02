@extends('admin::layouts.master')
@section('title', 'Ticket Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Ticket', 'url' => '#'],
        ['title' => 'Manager', 'url' => route('admin.ticket.manager')]
    ]
])
@endcomponent

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <!-- Nav tabs -->
                <ul class="nav nav-tabs" id="myTab">
                    <li><a href="#tickets" data-toggle="tab">Tickets</a></li>
                    <li><a href="#activity" data-toggle="tab">Activity</a></li>
                    <li><a href="#stats" data-toggle="tab">Stats</a></li>
                </ul>

                <!-- Tab panes -->
                <div class="tab-content">
                    <div class="tab-pane" id="tickets">
                        @include('admin::ticket.manager.templates._tickets')
                    </div>

                    <div class="tab-pane" id="activity">
                        <div class="row">
                            <div class="col-lg-12 text-center" style="margin-top:100px;">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>

                    </div>

                    <div class="tab-pane" id="stats">
                        <div class="row">
                            <div class="col-lg-12 text-center" style="margin-top:100px;">
                                <i class="fa fa-spinner fa-spin fa-3x fa-fw"></i>
                                <span class="sr-only">Loading...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ----------------------------------------------- --}}

    <div class="modal fade" id="moderate_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="moderate_model_title">Moderation</h4>
                </div>
                <div class="modal-body" id="moderate_model_content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-multi-moderate-button">Submit</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="multi_moderate_model" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="multi_moderate_model_title">Multi-Moderation</h4>
                </div>
                <div class="modal-body" id="multi_moderate_model_content"></div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submit-multi-moderation-button">Apply</button>
                </div>
            </div>
        </div>
    </div>

    {{ csrf_field() }}

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/adapters/jquery.js') }}"></script>

    <script type="text/javascript">
      var $ticketId = null;
      var $categoryList = '{{ json_encode((object) getList($categories->pluck('name', 'id')), true) }}';
      var $statusList = '{{ json_encode((object) getList($statuses->pluck('name', 'id')), true) }}';
      var $assignList = '{!! json_encode($assignments) !!}';
      var $startDate = '{{ time() }}';

      $(document).ready(function () {
        $(".ColVis_MasterButton").addClass("btn").addClass("btn-default").removeClass("ColVis_MasterButton").removeClass("ColVis_Button");
      });
    </script>

    <script src="{{ masset('js/plugins/moment-timezone-with-data.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.js"></script>
    <script src="{{ masset('js/ticket/ticket.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-manager-main.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-manager.js') }}"></script>
@endpush

@push('heads')
    <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css"
          rel="stylesheet"/>
    <link rel="stylesheet" href="{{ masset('js/plugins/bootstrap-multiselect/css/bootstrap-multiselect.css') }}"
          type="text/css">
@endpush