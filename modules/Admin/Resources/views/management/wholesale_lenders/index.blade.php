@extends('admin::layouts.master')

@section('title', 'Lenders')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Lenders', 'url' => route('admin.management.lenders')]
    ],
    'actions' => [
        ['title' => 'Add Lender', 'url' => route('admin.management.lenders.create')],
        ['title' => 'Download Template', 'url' => route('admin.management.lenders.download-template')],
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            @if(!empty($errors->first()))
                                <div class="col-md-12 row" style="margin-top: 15px;">
                                    <div class="row col-md-7">
                                        <div class="alert alert-danger alert-dismissible" role="alert">
                                            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <span>{{ $errors->first() }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                    <tr>
                                        <th>Default</th>
                                        <th>Lender</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Zip</th>
                                        <th>Send Email</th>
                                        <th>Clients</th>
                                        <th>States</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                            </table>
                            <form method='POST' id='lender_import' action='{{route('admin.management.lenders.import-excluded-users')}}' enctype='multipart/form-data'>
                                {{ csrf_field() }}
                                <table class="table table-striped table-bordered table-hover">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Excluded Users Import</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td style="width: 50%;">
                                                <select name="lender" id="lender" class="form-control _select">
                                                    <option value="">--Select--</option>
                                                    @foreach($lenders as $lender)
                                                        <option value="{{$lender->id}}">{{$lender->lender}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="file" name="lender_file" id="lender_file" value="" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                                <div class="submit">
                                    <input type="submit" id="lender_submit" value="Import" class="btn btn-success" />
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('css/management/wholesale_lenders/index.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ masset('js/management/wholesale_lenders/netdna.js') }}"></script>
    <script src="{{ masset('js/management/wholesale_lenders/index.js') }}"></script>
@endpush
