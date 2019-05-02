@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounting General Reports', 'url' => route('admin.accounting.general-reports.index')]
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
                    {!! Form::open(['route' => ['admin.accounting.general-reports.export'], 'id' => 'admin_form','class' => 'form-horizontal',]) !!}
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Report</label>
                        <div class="col-md-2">
                            {!! Form::select('report', $reports, null,
                                ['id' => 'report', 'class' => 'form-control', 'placeholder' => 'Choose report']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="submit" value="submit" name="submit" class="btn btn-primary">Download</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop