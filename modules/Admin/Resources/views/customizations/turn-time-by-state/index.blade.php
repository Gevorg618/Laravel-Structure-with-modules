@extends('admin::layouts.master')

@section('title', 'Turn Time by State')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Customizations', 'url' => '#'],
        ['title' => 'Turn Time by State', 'url' => route('admin.management.turn-time-by-state')]
    ]
])
@endcomponent

@section('content')
    @if($manage)
        <div class="modal fade" id="turntime-state-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
            <div class="modal-dialog" role="document">
                <form method="POST" action="{{ route('admin.management.turn-time-by-state.save') }}">
                    {{ csrf_field() }}
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title"></h4>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="days">Days</label>
                                <input type="text" class="form-control" name="days" id="days" placeholder="Enter Turn Time in Days" required>
                                <input type="hidden" class="form-control" name="state" id="state">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary" >Save changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="container">
                            <div id="map" style="height: 630px; width: 930px;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/us-map/lib/raphael.js') }}"></script>
    <script src="{{ masset('js/us-map/jquery.usmap.js') }}"></script>
    <script>
        var $stateTitles = {!! json_encode($stateTitles) !!}
        var $stateTitlesFull = {!! json_encode($stateTitlesFull) !!}
        var $statesList = {!! json_encode($statesList) !!}
        var $isAdminUser = {!! $manage !!}

        $(document).ready(function() {
            $('#map').usmap({
              'stateTitles': $stateTitles,
              'stateStyles': {
                fill: "#303d71",
                stroke: "#fff",
                "stroke-width": 1,
                "stroke-linejoin": "round",
                scale: [1, 1]
              },
              'stateHoverStyles': {
                fill: "#82a134",
                stroke: "#ADCC56",
                scale: [1.1, 1.1]
              },
              'labelBackingStyles': {
                fill: "#303d71",
                stroke: "#fff",
                "stroke-width": 1,
                "stroke-linejoin": "round",
                scale: [1, 1]
              },

              'labelBackingHoverStyles': {
                fill: "#82a134",
                stroke: "#ADCC56",
              },
              'labelTextStyles': {
                fill: "#fff",
                'stroke': 'none',
                'font-weight': 300,
                'stroke-width': 0,
                'font-size': '10px'
              },
              click: function(event, data) {
                showState(data);
                if($isAdminUser) {
                  manageState(data);
                }
              }
            });
        });

        function manageState(data)
        {
            $('#turntime-state-modal .modal-title').html(data.name + ' - Set Turn Time');
            $('#state').val(data.name);

            if($statesList[data.name]) {
                $('#days').val($statesList[data.name]);
            } else {
                $('#days').val('');
            }

            $('#turntime-state-modal').modal();
        }

        function showState(data)
        {
            if($stateTitlesFull[data.name]) {
                $message = $stateTitlesFull[data.name];
                $('.alert-state').html($message);
                $('.alert-state').removeClass('hidden');
            } else {
                $('.alert-state').addClass('hidden');
            }
        }
    </script>

@endpush
