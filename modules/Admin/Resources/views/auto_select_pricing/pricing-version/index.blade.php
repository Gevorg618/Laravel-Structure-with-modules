@extends('admin::layouts.master')

@section('title', 'Auto Select & Pricing')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Auto Select & Pricing', 'url' => '#'],
      ['title' => 'Pricing Versions', 'url' => route('admin.autoselect.pricing.versions.index')]
    ]
])
@endcomponent

@section('content') 
<style type="text/css" media="screen">
        .btn-default:focus,
        .btn-default:active,
        .btn-default.active {
            background-color: red;
            border-color: red;
        }
</style>
    <div class="row" id="nav-content">
        <div class="col-lg-12">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a data-toggle="tab" href="#pricing" data-type="pricing" >Pricing Versions </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#custom_pricing" data-type="custom_pricing" >Custom Pricing Versions </a>
                </li>
                <li>
                    <a data-toggle="tab" href="#import" data-type="import" >Import Version</a>
                </li>
                <li>
                    <a data-toggle="tab" href="#import_client" data-type="import_client" >Import Client Version </a>
                </li>
            </ul>
            <div class="tab-content">
                <div id="pricing" class="tab-pane fade in active">
                    @include('admin::auto_select_pricing.pricing-version.nav-tabs._pricing_version')
                </div>
                <div id="custom_pricing" class="tab-pane fade">
                    @include('admin::auto_select_pricing.pricing-version.nav-tabs.._custom_pricing_version')
                </div>
                <div id="import" class="tab-pane fade">
                    @include('admin::auto_select_pricing.pricing-version.nav-tabs._import_version')
                </div>
                <div id="import_client" class="tab-pane fade">
                    @include('admin::auto_select_pricing.pricing-version.nav-tabs._import_client_version')
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" id="request_type" value="pricing">

    <!-- Modal -->
    <div id="myModal" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg" style="width: 1100px;">
        <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body over_modal">

                    
                </div>
                <div class="modal-body under_modal"></div>
                <div class="modal-footer without_submit">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary save_data" data-url="#" data-type=""> Save </button>
                </div>
            </div>
        </div>
    </div>

@stop
@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/modules/admin/pricing-version/main.js') }}"></script>
@endpush
