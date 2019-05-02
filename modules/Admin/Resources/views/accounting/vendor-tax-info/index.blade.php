@extends('admin::layouts.master')

@section('title', 'Vendor Tax Info')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Vendor Tax Info', 'url' => route('admin.vendor_tax_info.index')]
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
                    'route' => ['admin.vendor_tax_info.export'],
                    'id' => 'admin_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                    <div class="form-group">
                        <label for="year" class="col-md-2 control-label">Year</label>
                        <div class="col-md-2">
                            {!! Form::select('year', $years, Request::get('year', date('Y')),
                                ['id' => 'year', 'class' => 'form-control', 'placeholder' => 'Year']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button value="submit" name="submit" class="btn btn-primary">Download</button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
@stop