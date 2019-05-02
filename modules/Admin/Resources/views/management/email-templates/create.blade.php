@extends('admin::layouts.master')

@section('title', 'Email Templates')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Email Templates', 'url' => route('admin.management.email-templates')],
  ['title' => 'New Email Template', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-8">
            {{ Form::model($emailTemplate, ['route' => [$emailTemplate->id ? 'admin.management.email-templates.update' : 'admin.management.email-templates.create', 'id' => $emailTemplate->id], 'class' => 'form-horizontal']) }}
            @if($emailTemplate->id)
                {{ method_field('PUT') }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($emailTemplate->id)?'Updating':'Creating' }}</h3>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::text('title', old('title', $emailTemplate->title), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        {{ Form::label('category', 'Category', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::select('category',$categories,old('category',$emailTemplate->category), ['class' => 'form-control']) }}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="form-group">
                                        {{ Form::label('content', 'Content', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('content', old('content', $emailTemplate->content), ['class' => 'form-control summernote']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($emailTemplate->id)?'Update':'Save' }}</button>
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
<script type="text/javascript">
    $(document).ready(function () {
        $('.datepicker').datetimepicker({
            format: 'YYYY-MM-DD'
        });
    });
</script>
@endpush