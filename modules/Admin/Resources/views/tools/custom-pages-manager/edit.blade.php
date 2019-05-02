@extends('admin::layouts.master')

@section('title', 'Custom Pages Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Custom Pages Manager', 'url' => '#'],
      ['title' => 'Custom Pages', 'url' => route('admin.tools.custom-pages-manager.index')],
      ['title' => 'Update Custom Page', 'url' => '#']
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6 col-lg-offset-3">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">Updating Custom Page: {{ $customPage->title }}</h3>
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
                    {{ Form::model($customPage, [ 'route' => ['admin.tools.custom-pages-manager.update', $customPage->id ], 'method' => 'put', 
                                    'class' => 'form-group',
                                    'id' => 'custom-page-store-form',
                                    'file'=> 'true', 'enctype' => 'multipart/form-data']) }}
                        @include('admin::tools.custom-pages-manager.partials._form', ['button_label' => 'Update'])
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
@stop