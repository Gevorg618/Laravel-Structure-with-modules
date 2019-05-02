@extends('admin::layouts.master')

@section('title', 'Viewing Counties')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'AutoSelect & Pricing', 'url' => '#'],
        ['title' => 'Viewing Counties', 'url' => '#']
    ]
])
@endcomponent

<link href="{{ masset('css/auto_select_pricing/auto_select_counties/edit.css') }}" rel="stylesheet" />

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="container counties_content">
                            <div class="counties form-check state_name">
                                <label>
                                    {{$stateName}} 
                                    <input type="checkbox" class="county" id="{{$slug}}" value="{{$stateName}}" {{count($stateAllCounties) == count($stateSelectedCounties) ? 'checked' : ''}}>
                                </label> 
                            </div>
                            <form action="{{route('admin.autoselect.counties.update', ['slug' => $slug])}}" method="POST">
                                <input type="hidden" name="_method" value="PUT" />
                                {{ csrf_field() }}
                                @foreach($stateAllCounties as $state) 
                                    <div class="col-md-3">
                                        <div class="counties form-check">
                                            <label>
                                                {{$state->county}} 
                                                <input type="checkbox" class="county county_checkbox" name="{{$slug}}_{{$state->county}}" value="{{$state->county}}" {{array_search($state->county, array_column($stateSelectedCounties, 'county', 'id')) ? 'checked' : ''}}>
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="col-md-12 button_content">
                                    <button class="btn btn-success btn_success">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script type="text/javascript">
    $(document).ready(function() {
        $('#{{$slug}}').click(function() {
            if ($(this).is(':checked')) {
                $('.county').prop('checked', true)
            } else {
                $('.county').prop('checked', false)
            }
        });

        $('.county_checkbox').click(function() {
            var check =  $('.county_checkbox:checked').length;
            if ($(this).is(':checked')) {
                if({{count($stateAllCounties)}} == check) {
                    $('#{{$slug}}').prop('checked', true)
                }
            } else {
                $('#{{$slug}}').prop('checked', false)
            }
        });
    });
</script>
@endpush