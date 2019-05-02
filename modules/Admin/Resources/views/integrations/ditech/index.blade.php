@extends('admin::layouts.master')

@section('title', 'Integrations')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Ditech Manager', 'url' => '#']
    ],
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
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
                        <div class="form-body">
                            <div class="row">
                                {!! Form::open(['route' => 'admin.reports.ditech.download', 'id' => 'form-report-show',  'enctype' => "multipart/form-data"]) !!}
                                    <div class="form-group col-md-6">
                                        <label name="daterange" class="control-label col-lg-3 col-xs-12"> Date Range
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {!! Form::text('daterange', null, ['class' => 'form-control daterange']) !!}
                                        </div>                                        
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label name="client" class="control-label col-lg-3 col-xs-12"> Loan Numbers
                                        </label>
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('loanNumbers',  null, ['class' => 'form-control', 'placeholder' => 'Loan Number Ids, One Per Line']) }}
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12">
                                        <div class="col-lg-6 col-xs-12">
                                            <button type="submit" class="btn btn-primary">Download</button>
                                        </div>
                                    </div>    
                                {!! Form::close() !!}    
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')

    <script type="text/javascript">

        $(function() {
            $('input[name="daterange"]').daterangepicker();
        });

    </script>
@endpush
