@extends('admin::layouts.master')

@section('title', 'Auto Select Turn Times')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'AutoSelect Pricing Fees', 'url' => route('admin.autoselect.pricing.fees.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>AutoSelect Pricing Version Fees</b></h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable_default">
                                <thead>                                
                                    <tr>
                                        <th>Prising Version</th>
                                        <th>Total Records</th>
                                        <th>Supposed To Be</th>
                                        <th>Created By</th>
                                        <th>Created Date</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>
                                <tbody>
                                        @foreach($versionFees as $versionFee)
                                        <tr>
                                            <td>{{ $versionFee->title }}</td>
                                            <td>{{ $versionFee->autoSelectPricingVersionFees->count() }}</td>
                                            <td>{{ $suppostedToBe }}</td>
                                            <td>
                                                @if($versionFee->autoSelectPricingVersionFees->count() > 0)
                                                {{ $versionFee->autoSelectPricingVersionFees->first()->editedBy->userData->firstname.' '.$versionFee->autoSelectPricingVersionFees->first()->editedBy->userData->lastname }}
                                                @else 
                                                    None
                                                @endif
                                            </td>
                                            <td>
                                                @if($versionFee->autoSelectPricingVersionFees->count() > 0)
                                                    {{ date('m/d/Y H:i', $versionFee->autoSelectPricingVersionFees->first()->created_date)}}
                                                @else 
                                                    None
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li>
                                                            <a href="{{ route('admin.autoselect.pricing.version.fees.states', $versionFee->id) }}">
                                                                <i class="fa fa-list "></i> 
                                                                View Version States
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.autoselect.pricing.version.fees.state', $versionFee->id) }}"><i class="fa fa-list "></i> View All</a>
                                                        </li>
                                                    </ul>
                                                </div>                                                
                                            </td>
                                        </tr>
                                        @endforeach 
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>AutoSelect Pricing Group Fees</b>
                    </h2>
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable_client_specific">
                                <thead>
                                <tr>
                                    <th>Group Title</th>
                                    <th>Total Records</th>
                                    <th>Supposed To Be</th>
                                    <th>Created By</th>
                                    <th>Created Date</th>
                                    <th>Options</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($createdGroups as $createdGroup)
                                        <tr>
                                            <td>{{ $createdGroup->client->descrip }}</td>
                                            <td>{{ $createdGroup->groupCount() }}</td>
                                            <td>{{ $suppostedToBe }}</td>
                                            <td>{{ $createdGroup->editedBy->userData->firstname.' '.$createdGroup->editedBy->userData->lastname }}</td>
                                            <td>{{ date('m/d/Y H:i', $createdGroup->created_date)}}</td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
                                                    <ul class="dropdown-menu pull-right" role="menu">
                                                        <li><a href="{{ route('admin.autoselect.pricing.group.fees.states', $createdGroup->group_id) }}"><i class="fa fa-list "></i> View Group States </a></li>
                                                        <li><a href="{{ route('admin.autoselect.pricing.group.fees.state', $createdGroup->group_id) }}"><i class="fa fa-list "></i> View All</a></li>
                                                        <li><a href="{{ route('admin.autoselect.pricing.group.fees.destroy', $createdGroup->group_id) }}"><i class="fa fa-trash"></i> Delete</a></li>
                                                    </ul>
                                                </div>                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <h2><b>Create New Group Pricing Version</b>
                    </h2>
                    <div class="panel-body panel-body-table">
                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        {{ Form::open([ 'route' => 'admin.autoselect.pricing.group.fees.store','class' => 'form-group','id' => 'new-group-pricing-version'])}}
                            <div class="form-group col-md-6">
                                <div class="col-lg-12 col-xs-12">
                                    {{ Form::select('group_id', $groups, null, ['class' => 'form-control selectpicker']) }}
                                    <span class="help-block client-error-block"></span>
                                </div>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary pull-right">Create</button>
                            </div>
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script>
$(document).ready(function () {
    $('#datatable_default').DataTable({
        "pageLength": 5
    });
    $('#datatable_client_specific').DataTable({
        "pageLength": 5
    });
});
</script>
@endpush

