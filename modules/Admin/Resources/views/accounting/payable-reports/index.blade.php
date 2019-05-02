@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounts Payable Report', 'url' => route('admin.accounting.payable-reports.index')]
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
                    {!! Form::open([ 'route' => ['admin.accounting.payable-reports.export'],'id' => 'admin_form', 'class' => 'form-horizontal',]) !!}
                    <div class="form-group">
                        <label for="date_from" class="col-md-2 control-label">Date From</label>

                        <div class="col-md-2">
                            {!! Form::text('date_from', Request::get('date_from'),['id' => 'date_from', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="date_to" class="col-md-2 control-label">Date To</label>
                        <div class="col-md-2">
                            {!! Form::text('date_to', Request::get('date_to'), ['id' => 'date_to', 'class' => 'datepicker form-control', 'placeholder' => 'Date From']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">States</label>
                        <div class="col-md-10">
                            {!! Form::select('states[]', $states, Request::get('states'), ['id' => 'states','class' => 'form-control bootstrap-multiselect','multiple' => 'multiple'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="clients" class="col-md-2 control-label">Clients</label>
                        <div class="col-md-10">
                            {!! Form::select('clients[]', $clients,Request::get('clients'),['id' => 'clients','class' => 'form-control bootstrap-multiselect','multiple' => 'multiple'])!!}
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-10">
                            <button type="button" id="accounts_payable_show" value="submit" name="submit" class="btn btn-primary">Search</button>
                            <button type="submit" value="export" name="export" id="export_al_payable"
                                    class="btn btn-primary">Download CSV
                            </button>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive" id="payables_data"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush