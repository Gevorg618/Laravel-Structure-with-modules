@extends('admin::layouts.master')
@section('title', 'Edit question')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'QC', 'url' => '#'],
        ['title' => 'Data Collection', 'url' => route('admin.qc.collection.index')],
        ['title' => 'Edit', 'url' => '']
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="col-lg-8 col-lg-offset-2 col-md-12 data_collection">
                        <a class="btn btn-sm btn-primary"
                           href="{{ route('admin.qc.collection.index') }}"><< Back</a>

                        @if (count($errors) > 0)
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!! Form::model($row, [
                            'route' => ['admin.qc.collection.update', 'id' => $row->id],
                            'id' => 'admin_form',
                            'class' => 'form-horizontal',
                            'method' => 'PUT'
                        ]) !!}

                        @include('qc.data_collection.partials.fields')

                        <hr>

                        <div class="form-group">
                            <div class="col-md-10">
                                <button type="submit" value="submit" name="submit" class="btn btn-primary">Save</button>
                            </div>
                        </div>

                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-rules.js') }}"></script>
@endpush