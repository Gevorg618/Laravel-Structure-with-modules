@extends('admin::layouts.master')

@section('title', 'Documents')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Global Documents', 'url' => route('admin.document.global.index')],
        ['title' => 'Add Record', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Basic Info</h3>
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
                    {{ Form::model($document, [ 'route' => ['admin.document.global.update', $document->id ], 'method' => 'put', 
                                    'class' => 'form-group', 'file'=> 'true', 'enctype' => 'multipart/form-data']) }}
                        @include('admin::document.global.partials._form', ['button_label' => 'Update'])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
<script>
$(document).ready(function () {
    $('.states').select2();
});
</script>
@endpush