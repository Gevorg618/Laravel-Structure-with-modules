@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'Auto Select Turn Times', 'url' => route('admin.autoselect.turn.times.index')],
      ['title' => 'Add Client Specific Turn Time', 'url' => '#']
    ]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
                </div>
                <div class="panel-body">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                     {{ Form::model($turnTimes, [ 'route' => ['admin.autoselect.turn.times.update', $id ], 'method' => 'put', 
                                    'class' => 'form-group',
                                    'id' => 'form',
                                    'file'=> 'true', 'enctype' => 'multipart/form-data']) }}
                        @include('admin::auto_select_pricing.turn-times.partials._form', ['button_label' => 'Update', 'clientId' => $id])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')

@endpush