@extends('admin::layouts.master')

@section('title', 'Appraisal Addendas New Addenda')


@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Customizations', 'url' => '#'],
  ['title' => 'Addendas', 'url' => route('admin.appraisal.addendas')],
  ['title' => 'New Addeda', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($addenda, ['route' => [$addenda->id ? 'admin.appraisal.addendas.update' : 'admin.appraisal.addendas.create', 'id' => $addenda->id], 'class' => 'form-horizontal']) }}
            @if($addenda->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($addenda->id)?'Updating':'Creating' }}</h3>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('descrip', 'Description', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('descrip', old('descrip', $addenda->descrip), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('invest', 'Invest', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('invest', ['N' =>'No','Y' => 'Yes'],old('invest', $addenda->invest), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('price', 'Price', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('price',old('price', $addenda->price), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($addenda->id)?'Update':'Save' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop