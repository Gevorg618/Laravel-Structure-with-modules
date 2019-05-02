@extends('admin::layouts.master')

@section('title', 'Announcements')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Management', 'url' => '#'],
  ['title' => 'Announcements', 'url' => route('admin.management.announcements')],
  ['title' => 'New Announcement', 'url' => '']
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-9">
            {{ Form::model($announcement, ['route' => [$announcement->id ? 'admin.management.announcements.update' : 'admin.management.announcements.create', 'id' => $announcement->id], 'class' => 'form-horizontal']) }}
            @if($announcement->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$announcement->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($announcement->id)?'Updating':'Creating' }}</h3>
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
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $announcement->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('is_active', 'Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('is_active',[0 => 'No',1 => 'Yes'],old('is_active', $announcement->is_active), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('from_date', 'Date to Show', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('from_date',old('from_date', toDate($announcement->from_date)), ['class' => 'form-control datepicker']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('expired_date', 'Date To Expire', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('expired_date',old('expired_date', toDate($announcement->expired_date)), ['class' => 'form-control datepicker']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('redirect_title', 'Redirect Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('redirect_title',old('redirect_title', $announcement->redirect_title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('redirect_link', 'Redirect Link', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('redirect_link',old('redirect_link', $announcement->redirect_link), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3 col-md-offset-1">
                                    <div class="col-lg-12">
                                        @foreach(userTypes() as $type)
                                            <div class="form-group">
                                                {{ Form::checkbox('user_types[]', $type->id,$announcement->userType->contains('id',$type->id)?1:0,['id' => $type->descrip ]) }}
                                                {{ Form::label($type->descrip, $type->descrip) }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="col-lg-12 col-xs-12">
                                            {{ Form::textarea('content', old('content', $announcement->content), ['class' => 'form-control summernote']) }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="ibox-footer">
                                    <button class="btn btn-success pull-right">{{ ($announcement->id)?'Update':'Save' }}</button>
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
    $(document).ready(function(){
        $('.datepicker').datetimepicker({
            format:'YYYY-MM-DD HH:mm'
        });
    });
</script>
@endpush