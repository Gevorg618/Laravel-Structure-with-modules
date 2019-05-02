@extends('admin::layouts.master')

@section('title', 'UCDP Business Units Manager')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Appraisal', 'url' => '#'],
  ['title' => 'UCDP Business Units Manager', 'url' => route('admin.appraisal.ucdp-unit')],
  ['title' => 'New Business Units', 'url' => '']
]
])
@endcomponent
@push('heads')
<link href="{{ masset('css/plugins/bootstrap3-editable/css/bootstrap-editable.css') }}"
      rel="stylesheet"/>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-10">
            {{ Form::model($ucdpUnit, ['route' => [$ucdpUnit->id ? 'admin.appraisal.ucdp-unit.update' : 'admin.appraisal.ucdp-unit.create', 'id' => $ucdpUnit->id], 'class' => 'form-horizontal']) }}
            @if($ucdpUnit->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id',$ucdpUnit->id) }}
            @endif
            <div class="panel panel-success">
                <div class="panel-heading">
                    <h3 class="panel-title">{{ ($ucdpUnit->id)?'Updating':'Creating' }}</h3>
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
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            {{ Form::label('title', 'Title', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('title', old('title', $ucdpUnit->title), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('unit_id', 'Unit ID', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::text('unit_id',old('unit_id',$ucdpUnit->unit_id), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('is_active', 'Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('is_active',[0 => 'No',1 => 'Yes'],old('category',$ucdpUnit->is_active), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('fnm_active', 'FNM Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('fnm_active',[0 => 'No',1 => 'Yes'],old('category',$ucdpUnit->fnm_active), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('fre_active', 'FNM Active', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('fre_active',[0 => 'No',1 => 'Yes'],old('category',$ucdpUnit->fre_active), ['class' => 'form-control']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('appr_type', 'Appraisal Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('appr_type[]',$ucdpUnit->appr_type_all,$ucdpUnit->appr_type->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('loan_type', 'Loan Type', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('loan_type[]',$ucdpUnit->loan_type_all,$ucdpUnit->loan_type->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('clients', 'Clients', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('clients[]',$ucdpUnit->clients_all,$ucdpUnit->clients->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            {{ Form::label('lenders', 'Lenders', ['class' => 'col-lg-12 col-xs-12 required']) }}
                                            <div class="col-lg-12 col-xs-12">
                                                {{ Form::select('lenders[]',$ucdpUnit->lenders_all,$ucdpUnit->lenders->pluck('id')->all(), ['class' => 'form-control chosen','multiple' => 'multiple','data-live-search' => 'true']) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Fannie Mae SSN IDs</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="FNM">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($ucdpUnit->fnm_ssn))
                                    @foreach($ucdpUnit->fnm_ssn as $fnm_ssn)
                                        <tr>
                                            <td>
                                                <a class="fnm-xedit"
                                                   data-name="ssn_id" data-value="{{$fnm_ssn->ssn_id }}"
                                                   data-pk="{{ $fnm_ssn->id }}"
                                                   data-title="Enter Fnm SSN ID">
                                                </a>
                                            </td>
                                            <td>
                                                <a class="fnm-xedit"
                                                   data-name="title" data-value="{{$fnm_ssn->title }}"
                                                   data-pk="{{ $fnm_ssn->id }}"
                                                   data-title="Enter Fnm Title">
                                                </a>
                                            </td>
                                            <td width="100">
                                                <button type="button" class="btn btn-success ajax-fnm-edit"
                                                        data-id="{{ $fnm_ssn->id }}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger ajax-fnm-remove"
                                                        data-id="{{ $fnm_ssn->id }}"><i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-12">
                                    <button type="button" id="addFNM" class="btn btn-primary pull-right">Add row
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Freddie Mac SSN/TPO IDs</h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-striped table-bordered table-hover" id="FRE">
                                <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Remove</th>
                                </tr>
                                </thead>
                                <tbody>
                                @if(!empty($ucdpUnit->fre_ssn))
                                    @foreach($ucdpUnit->fre_ssn as $fre_ssn)
                                        <tr>
                                            <td>
                                                <a class="fre-xedit"
                                                   data-name="ssn_id" data-value="{{$fre_ssn->ssn_id }}"
                                                   data-pk="{{ $fre_ssn->id }}"
                                                   data-title="Enter Fre SSN ID">
                                                </a>
                                            </td>
                                            <td>
                                                <a class="fre-xedit"
                                                   data-name="title" data-value="{{$fre_ssn->title }}"
                                                   data-pk="{{ $fre_ssn->id }}"
                                                   data-title="Enter Fre Title">
                                                </a>
                                            </td>
                                            <td width="100">
                                                <button type="button" class="btn btn-success ajax-fre-edit"
                                                        data-id="{{ $fre_ssn->id }}"><i class="fa fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger ajax-fre-remove"
                                                        data-id="{{ $fre_ssn->id }}"><i class="fa fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6">
                                    <p class="freError"></p>
                                </div>
                                <div class="col-md-6">
                                    <button type="button" id="addFRE" class="btn btn-primary pull-right">Add row
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <button class="btn btn-success pull-right">{{ ($ucdpUnit->id)?'Update':'Save' }}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
@stop
@push('scripts')
<script src="{{ masset('js/plugins/bootstrap3-editable/js/bootstrap-editable.min.js') }}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        });
        $.fn.editable.defaults.send = 'auto';
        $.fn.editable.defaults.disabled = true;
        disabled = true;
        $('.fre-xedit').editable({
            url: '{{ route('admin.appraisal.ucdp-unit.fre-edit') }}',
            type: 'text',
            success: function(responce) {
                if(!responce.success){
                    $('.freError').html(response);
                }
            }
        });
        $('.ajax-fre-edit').click(function () {
            $(this).toggleClass('btn-success').toggleClass('btn-primary');
            $(this).find('i').toggleClass('fa-edit').toggleClass('fa-check');
            $('.fre-xedit').editable('toggleDisabled');
        });
        $('.fnm-xedit').editable({
            url: '{{ route('admin.appraisal.ucdp-unit.fnm-edit') }}',
            type: 'text'
        });
        $('.ajax-fnm-edit').click(function () {
            $(this).toggleClass('btn-success').toggleClass('btn-primary');
            $(this).find('i').toggleClass('fa-edit').toggleClass('fa-check');
            $('.fnm-xedit').editable('toggleDisabled');
        });
        update = '{{ $ucdpUnit->id }}';
        $('.chosen').selectpicker();
        var fnm_count = 0;
        $('#addFNM').click(function () {
            $('#FNM tbody').append(
                    '<tr id="fnm_' + fnm_count + '">' +
                    '<td>' +
                    '<input type="text" name="fnm_ssn[' + fnm_count + '][ssn_id]" class="form-control">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="fnm_ssn[' + fnm_count + '][title]" class="form-control">' +
                    '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-danger fnm-remove" data-index="' + fnm_count + '">Remove</button>' +
                    '</td>' +
                    '</tr>'
            );
            fnm_count++;
        });
        $(document).on('click', '.fnm-remove', function (e) {
            e.preventDefault();
            var index = $(this).data('index');
            $('#fnm_' + index).remove();
        });
        var fre_count = 0;
        $('#addFRE').click(function () {
            $('#FRE tbody').append(
                    '<tr id="fre_' + fre_count + '">' +
                    '<td>' +
                    '<input type="text" name="fre_ssn[' + fre_count + '][ssn_id]" class="form-control">' +
                    '</td>' +
                    '<td>' +
                    '<input type="text" name="fre_ssn[' + fre_count + '][title]" class="form-control">' +
                    '</td>' +
                    '<td>' +
                    '<button type="button" class="btn btn-danger fre-remove" data-index="' + fre_count + '">Remove</button>' +
                    '</td>' +
                    '</tr>'
            );
            fre_count++;
        });
        if (!update) {
            $('#addFRE').trigger('click');
            $('#addFNM').trigger('click');
        }
        $(document).on('click', '.fre-remove', function (e) {
            e.preventDefault();
            var index = $(this).data('index');
            $('#fre_' + index).remove();
        });
        $(document).on('click', '.ajax-fnm-remove', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var tr = $(this).closest('tr');
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.appraisal.ucdp-unit.deleteFNM') }}',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        tr.fadeOut();
                    }
                }

            });
        });
        $(document).on('click', '.ajax-fre-remove', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            var tr = $(this).closest('tr');
            $.ajax({
                type: 'POST',
                url: '{{ route('admin.appraisal.ucdp-unit.deleteFRE') }}',
                data: {id: id},
                success: function (data) {
                    console.log(data);
                    if (data.success) {
                        tr.fadeOut();
                    }
                }

            });
        });
    });
</script>
@endpush