@extends('admin::layouts.master')

@section('title', 'Viewing Appraiser Fees For '.$checkState->state)

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'Auto Select Appraiser Fees', 'url' => route('admin.autoselect.appraiser.fees.index')],
      ['title' => $checkState->state,           'url' => route('admin.autoselect.appraiser.fees.state.form', $checkState->abbr)],
    ]
])
@endcomponent
@push('style')
<link rel="stylesheet" href="{{ masset('css/autocomplete-pricing/appraiser-fee-pricing-form.css') }}">
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title"></h3>
                </div>
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
                    {{ Form::open(['route' => ['admin.autoselect.appraiser.fees.store.form', $checkState->abbr],  'class' => 'form-horizontal', 'method' => 'POST']) }}
                    <div class="form-body">
                        <div class="table-responsive">
                            <table class="fixed_headers table table-striped table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>State</th>
                                        <th>Amount</th>
                                        <th>FHA Amount</th>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>
                                            <div class="col-md-4">
                                                {{ Form::number('all_amount', 0, ['class' => 'form-control', 'id' => 'all_amount', 'step' =>'0.01']) }}
                                            </div>
                                            <button class="btn btn-small" id="all_amount_button" type="button">Set</button>
                                        </td>
                                        <td>
                                            <div class="col-md-4">
                                                {{ Form::number('all_fhaamount', 0, ['class' => 'form-control', 'id' => 'all_fha_amount', 'step' =>'0.01']) }}
                                            </div>

                                            <button class="btn" id="all_fhaamount_button" type="button">Set</button>
                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($types as $typeId => $type)
                                    <tr>
                                        <td>{{ $type }}</td>
                                        <td>
                                            {{ Form::number('appr_fee_pricing['.$typeId.'][amount]', (isset($apprFeesPricing[$typeId])) ? $apprFeesPricing[$typeId]['amount'] : 0, ['class' => 'amount form-control', 'step' =>'0.01' ]) }}
                                        </td>
                                        <td>
                                            {{ Form::number('appr_fee_pricing['.$typeId.'][fhaamount]', (isset($apprFeesPricing[$typeId])) ? $apprFeesPricing[$typeId]['fhaamount'] : 0, ['class' => 'fhaamount form-control', 'step' =>'0.01']) }}
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="row m-t-lg">
                            <div class="col-md-12 ">
                                {!! Form::submit('Submit', ['class' => 'btn btn-success form-control']) !!}
                            </div>
                        </div>
                    </div>
                    {{Form::close()}}
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script type="text/javascript" src="{{ masset('js/autocomplete-pricing/appraiser-fee-pricing-form.js') }}"></script>
@endpush

