@extends('admin::layouts.master')

@section('title', 'Lenders')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Lenders', 'url' => route('admin.management.lenders')],
        ['title' => 'Viewing Lender', 'url' => route('admin.management.lenders.edit', ['id' => $lender->id])],
        ['title' => 'Edit Proposed Loan Amount', 'url' => '#'],
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="container">
                            <form action="{{route('admin.management.lenders.update-proposed')}}" id="update-proposed" method="POST">
                                {{csrf_field()}}
                                <input type="hidden" name="proposed_id" value="{{$proposed->id}}"/>
                                <input type="hidden" name="lender_id" value="{{$lender->id}}"/>
                                <input type="hidden" name="_method" value="PUT">
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="title" class="col-md-2 required">Title</label>
                                        <div class="col-md-8">
                                            <input type="text" name="title" id="title" class="form-control" value="{{$proposed->title}}" placeholder="Title"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="range_start" class="col-md-2 required">Range Start</label>
                                        <div class="col-md-8">
                                            <input type="text" name="range_start" id="range_start" class="form-control" value="{{$proposed->range_start}}" placeholder="Range Start"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="range_end" class="col-md-2 required">Range End</label>
                                        <div class="col-md-8">
                                            <input type="text" name="range_end" id="range_end" class="form-control" value="{{$proposed->range_end}}" placeholder="Range End"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="amount" class="col-md-2 required">Amount</label>
                                        <div class="col-md-8">
                                            <input type="text" name="amount" id="amount" class="form-control" value="{{$proposed->amount}}" placeholder="Amount"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="appraisalTypes" class="col-md-2">Appraisal Types</label>
                                        <div class="col-md-8">
                                            <select name="appraisalTypes[]" id="appraisalTypes" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                @foreach($apprTypes as $apprType)
                                                    <option value="{{$apprType->id}}" {{!is_null($selectedApprTypes->where('proposed_id', $proposed->id)->where('appr_type_id', $apprType->id)->first()) ? 'selected' : ''}}>{{$apprType->description}}</option>
                                                @endforeach
                                            </select>
                                            <p>Select appraisal types if this proposed loan only applies to certain appraisal types. Leave unchanged if this will apply to all.</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="states" class="col-md-2">States</label>
                                        <div class="col-md-8">
                                            <select name="states[]" id="states" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                @foreach($states as $key => $value)
                                                    <option value="{{$key}}" {{!is_null($selectedStates->where('proposed_id', $proposed->id)->where('state', $key)->first()) ? 'selected' : ''}}>{{$value}}</option>
                                                @endforeach
                                            </select>
                                            <p>Select states if this proposed loan only applies to certain states. Leave unchanged if this will apply to all.</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="addendas" class="col-md-2">Addendas</label>
                                        <div class="col-md-8">
                                            <select name="addendas[]" id="addendas" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                @foreach($addendas as $addenda)
                                                    <option value="{{$addenda->id}}" {{!is_null($selectedAddendas->where('proposed_id', $proposed->id)->where('addenda_id', $addenda->id)->first()) ? 'selected' : ''}}>{{$addenda->descrip}}</option>
                                                @endforeach
                                            </select>
                                            <p>Select addendas if this proposed loan only applies to certain addendas. Leave unchanged if this will apply to all.</p>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </form>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-1">
                                        <button type="button" class="btn btn-success update-proposed">Save</button>
                                    </div>
                                    <div class="col-md-1">
                                        <form action="{{route('admin.management.lenders.delete-proposed')}}" method="POST">
                                            {{csrf_field()}}
                                            <input type="hidden" name="proposed_id" value="{{$proposed->id}}"/>
                                            <input type="hidden" name="lender_id" value="{{$lender->id}}"/>
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button type="submit" class="btn btn-danger">Delete</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/management/wholesale_lenders/proposed/create.js') }}"></script>
@endpush
