@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'AutoSelect Pricing Version Fees', 'url' => route('admin.autoselect.pricing.fees.index')],
      ['title' => 'View Version State', 'url' => '#']
    ]
])
@endcomponent

@push('style')
<link rel="stylesheet" href="{{ masset('css/autocomplete-pricing/loading-spinner.css') }}">
@endpush

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    @if($all)
                        <h3 class="panel-title">Viewing Pricing Version Fees <b><i>{{ $version->title }}</i></b> For All States
                        </h3>
                    @else
                        <h3 class="panel-title">Viewing Pricing Version Fees <b><i>{{ $version->title }}</i></b> For States <b><i>{{ $state->state }} ({{ $state->abbr }})</i></b>
                        </h3>
                    @endif
                    
                </div>
                <div class="panel-body">
                    <div class="load_box">
                        <i class="fa fa-spinner fa-spin fa-3x" aria-hidden="true"></i>
                    </div>
                    <div class="container">
                        @if($all)
                            <input type="hidden" id="group_id" value="{{ $version->id }}">
                            <div class="panel-group accordion" id="accordion1">
                                @foreach($states as $state)
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h4 class="panel-title">
                                                <a class="accordion-toggle collapsed success" data-state='{{ $state->abbr }}' data-toggle="collapse" data-parent="#accordion1" href="#collapse_{{ $state->abbr }}" aria-expanded="false"> {{ $state->state }} ({{ $state->abbr }})
                                                </a>
                                            </h4>
                                        </div>
                                        <div id="collapse_{{ $state->abbr }}" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                            <div class="conteiner">
                                               <div id="load_{{ $state->abbr }}" class="loader_box text-center">
                                                    <i class="fa fa-spinner fa-spin fa-3x" aria-hidden="true"></i>
                                               </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach                                
                            </div>
                        @else
                            {{ Form::open([ 'route' => ['admin.autoselect.pricing.version.fees.update', $version->id, $state->abbr ], 'method' => 'put', 
                                    'class' => 'form-group',
                                    'id' => 'form',
                                    'file'=> 'true', 'enctype' => 'multipart/form-data']) }}
                                @include('admin::auto_select_pricing.version-fees.partials._form-group', ['button_label' => 'Update'])
                            {{ Form::close() }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
@if($all)
    <script type="text/javascript" src="{{ masset('js/autocomplete-pricing/pricing-version-fee-accordion.js') }}"></script>
@endif
    <script type="text/javascript" src="{{ masset('js/autocomplete-pricing/pricing-version-fee-form.js') }}"></script>
@endpush