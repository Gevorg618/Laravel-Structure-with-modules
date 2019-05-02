@extends('admin::layouts.master')

@section('title', $template->id ? 'Updating Template' : 'New Template')


@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Template', 'url' => route('admin.tools.templates')],
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-6">
            {{ Form::model($template, ['route' => [$template->id ? 'admin.tools.templates.update' : 'admin.tools.templates.create', 'id' => $template->id], 'class' => 'form-horizontal']) }}
            @if($template->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('id', old('id', $template->id), ['class' => 'form-control']) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($template->id)?'Updating':'Creating' }}</h3>
                </div>
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

                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('name', 'Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('name', old('name', $template->name), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        {{ Form::label('source', 'Source', ['class' => 'col-lg-3 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('source', old('source', $template->source), ['class' => 'form-control ']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($template->id)?'Update':'Save' }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{ Form::close() }}
    </div>
@stop

@push('scripts')
  <script src="{{ masset('js/modules/admin/templateCodeMirror.js')  }}"></script>
@endpush
