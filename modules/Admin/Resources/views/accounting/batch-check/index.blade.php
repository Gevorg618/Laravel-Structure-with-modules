@extends('admin::layouts.master')

@section('title', 'Batch Check Payment')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Daily Batch', 'url' => route('admin.accounting.batch-check.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
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
                    <div class="panel-body">
                        <h1>Batch Check Payment</h1>
                        <div class="pull-left col-md-5">
                            {!! Form::open([
                                'route' => ['admin.accounting.batch-check.data'],
                                'id' => 'batch_check_form',
                                'class' => 'form-horizontal',
                                'method' => 'POST'
                            ]) !!}
                            <div class="form-group">
                                <label for="date_from" class="col-md-2 control-label">Date From</label>

                                <div class="col-md-6">
                                    {!! Form::text('date_from', null,
                                        ['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']
                                    ) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_to" class="col-md-2 control-label">Date To</label>
                                <div class="col-md-6">
                                    {!! Form::text('date_to', null,
                                        ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date To']
                                    ) !!}
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="date_to" class="col-md-2 control-label">DateType</label>
                                <div class="col-md-6">
                                    {!! Form::select('type', ['all' => 'Both', 'ordered' => 'Ordered Date', 'delivered' => 'Date Delivered'], null,
                                        ['id' => 'type', 'class' => 'form-control', 'placeholder' => 'Date Type']
                                    ) !!}
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
                            <div class="clear"></div>


                            <div class="form-group row">
                                <div class="col-md-10">
                                    <button type="reset" class="btn btn-primary">Reset Filters</button>
                                    <button type="submit" value="show_orders" name="show_orders" id="show_batch_check_orders"
                                            class="btn btn-primary">Show orders
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <div class="clear"></div>
                        </div>
                    </div>
                        <div class="table-responsive" id="show_orders_div"></div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush
