@extends('admin::layouts.master')
@section('title', 'Edit category')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'QC', 'url' => '#'],
        ['title' => 'Checklist', 'url' => route('admin.qc.checklist.index')],
        ['title' => 'Edit Category', 'url' => '']
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <a class="btn btn-sm btn-primary"
                       href="{{ route('admin.qc.checklist.index') }}"><< Back</a>

                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    {!! Form::model($category, [
                        'route' => ['admin.qc.checklist.update_category', 'id' => $category->id],
                        'id' => 'admin_form',
                        'class' => 'form-horizontal',
                        'method' => 'PUT'
                    ]) !!}
                    <div class="col-md-12">
                        <h2>Edit category</h2>
                    </div>

                    @include('management.checklist.partials.category_fields')

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush