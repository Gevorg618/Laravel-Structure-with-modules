@extends('admin::layouts.master')

@section('title', 'Customizations')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Create Appraisal Order Type', 'url' => '#']
    ],
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
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
                        <div class="form-body">
                            <div class="row">
                                {{ Form::open([ 'route' => 'admin.appraisal.appr-types.store', 'class' => 'form-group', 'id' => 'create-order'])}}
                                    @include('admin::appraisal.types.partials._form', ['button_label' => 'Create'])
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ masset('js/appraisal/types/main.js') }}"></script>
@endpush