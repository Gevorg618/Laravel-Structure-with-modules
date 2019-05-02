@extends('admin::layouts.master')

@section('title', 'Accounting')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Accounts Receivable Report', 'url' => route('admin.accounting.receivable-reports.index')]
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
                        <p>
                            <b>Individual Report:</b> Click on one of the amounts within each client row to download a
                            report for that client for a specific term.
                        </p>
                        <p>
                            <b>Multiple Client Report:</b> Select which clients you would like to view the report and
                            click the submit button. Clicking the checkbox at the top of each table will check/uncheck
                            all checkboxes within that table.
                        </p>

                        {!! Form::open([
                    'route' => ['admin.accounting.receivable-reports.invoiced'],
                    'id' => 'invoiced_form',
                    'class' => 'form-horizontal',
                    'method' => 'POST'
                ]) !!}
                        <div class="form-group">
                            <label for="clients" class="col-md-2 control-label">Filter</label>
                            <div class="col-md-2">
                                {!! Form::select('filter', $filters,
                            Request::get('filter'),
                            [
                                'id' => 'filter',
                                'class' => 'form-control',
                                'placeholder' => 'Choose filter'
                             ])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="clients" class="col-md-2 control-label">Show Credits</label>
                            <div class="col-md-2">
                                {!! Form::select('credits', $credits,
                            Request::get('credits'),
                            [
                                'id' => 'credits',
                                'class' => 'form-control',
                                'placeholder' => 'Choose Credits'
                            ])!!}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-10">
                                <button id="accounts_receivable_show" value="submit" name="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <div class="clear"></div>

                    <ul class="nav nav-tabs" id="accounts_receivable_tab">
                        <li class="active" ><a
                                              data-toggle="tabajax"
                                              data-url="{{ route('admin.accounting.receivable-reports.invoiced') }}"
                                              href="#invoiced">Invoiced Accounts</a>
                        </li>
                        <li><a data-toggle="tabajax" data-url="{{ route('admin.accounting.receivable-reports.noninvoiced') }}"
                               href="#noninvoiced">Non-Invoiced Accounts</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="invoiced" class="tab-pane fade in active">
                            <div class="table-responsive" id="invoiced_data"></div>
                        </div>
                        <div id="noninvoiced" class="tab-pane fade">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
