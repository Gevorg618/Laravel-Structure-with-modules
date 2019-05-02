@extends('admin::layouts.master')

@section('title', 'DocuVault Batch Check Payment')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'DocuVault Batch Check Payment', 'url' => route('admin.accounting.export-check.index')]
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
                    'route' => ['admin.accounting.batch-docuvault-check.show-orders'],
                    'id' => 'batch_show_orders_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                    <div class="form-group">
                        <label for="date_from" class="col-md-2 control-label">Date From</label>

                        <div class="col-md-2">
                            {!! Form::text('date_from', null,
                                ['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_to" class="col-md-2 control-label">Date To</label>
                        <div class="col-md-2">
                            {!! Form::text('date_to', null,
                                ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="ordertype" class="col-md-2 control-label">Order Type</label>
                        <div class="col-md-2">
                            {!! Form::select('ordertype', $orderTypes, null, [
                                'class' => 'form-control',
                                'placeholder' => 'Choose order type',
                                'id' => 'ordertype',
                            ]) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">Clients</label>
                        <div class="col-md-10">
                            {!! Form::select('clients[]', $clients,
                        null,
                        [
                            'id' => 'clients',
                            'class' => 'form-control bootstrap-multiselect',
                            'multiple' => 'multiple'
                        ])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <input type="reset" class="btn btn-info" id="reset_filters" name="reset" value='Reset'/>
                            <button type="submit" value="submit" name="submit" class="btn btn-primary" id="show_orders">Show Orders
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="panel-body panel-body-table" id="show_orders_div"></div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush