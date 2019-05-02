@extends('admin::layouts.master')

@section('title', 'GEO Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'GEO Manager', 'url' => '#'],
      ['title' => 'GEO', 'url' => route('admin.tools.geo.index')],
      ['title' => 'New GEO', 'url' => '#']
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
                    {{ Form::open([ 'route' => 'admin.tools.geo.store',
                                    'class' => 'form-group',
                                    'id' => 'geo-manager-form'])}}
                        @include('admin::tools.geo-manager.partials._form', ['button_label' => 'Create', 'adjacentStates' => null])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ masset('js/modules/admin/tools/geo-manager/validation.js') }}"></script>
@endpush