@extends('admin::layouts.master')

@section('title', 'Customizations')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
      ['title' => 'Create Appraisal Order Status', 'url' => '#']
    ]
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
                                {{ Form::open([ 'route' => 'admin.appraisal.appr-statuses.store', 'class' => 'form-group', 'id' => 'create-status'])}}
                                    @include('admin::customizations.statuses.partials._form', ['button_label' => 'Create'])
                                {{ Form::close() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ masset('js/appraisal/statuses/main.js') }}"></script>
<script>
    $(document).ready(function() {
        CKEDITOR.replace('vendor_auto_email_text');
        CKEDITOR.replace('client_auto_email_text');
    });
</script>
@endpush