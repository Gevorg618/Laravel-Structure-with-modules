@extends('admin::layouts.master')

@section('title', 'Export Check')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Export Check', 'url' => route('admin.accounting.export-check.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::open([
                    'route' => ['admin.accounting.export-check.export'],
                    'id' => 'admin_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Check Number</label>
                        <div class="col-md-2">
                            {!! Form::text('check_number', null,
                                ['id' => 'check_number', 'class' => 'form-control', 'placeholder' => 'Check Number']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="submit" value="submit" name="submit" class="btn btn-primary">Export</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop