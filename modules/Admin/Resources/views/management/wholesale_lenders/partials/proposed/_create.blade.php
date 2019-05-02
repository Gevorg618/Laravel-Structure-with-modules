@extends('admin::layouts.master')

@section('title', 'Lenders')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Lenders', 'url' => route('admin.management.lenders')],
        ['title' => 'Viewing Lender', 'url' => route('admin.management.lenders.edit', ['id' => $lender->id])],
        ['title' => 'Add Proposed Loan Amount', 'url' => '#'],
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
                            <form action="{{route('admin.management.lenders.create-proposed')}}" method="POST">
                                {{csrf_field()}}
                                @if(!empty($errors->first()))
                                    <div class="col-md-12" style="margin-top: 15px;">
                                        <div class="row col-md-7">
                                            <div class="alert alert-danger alert-dismissible" role="alert">
                                                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <span>{{ $errors->first() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <input type="hidden" name="lender_id" value="{{$lender->id}}"/>
                                <div class="col-md-8">
                                    <div class="form-group row">
                                        <label for="title" class="col-md-2 required">Title</label>
                                        <div class="col-md-8">
                                            <input type="text" name="title" id="title" class="form-control" value="{{old('title')}}" placeholder="Title"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="range_start" class="col-md-2 required">Range Start</label>
                                        <div class="col-md-8">
                                            <input type="number" name="range_start" id="range_start" value="{{old('range_start')}}" class="form-control" placeholder="Range Start"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="range_end" class="col-md-2 required">Range End</label>
                                        <div class="col-md-8">
                                            <input type="number" name="range_end" id="range_end" value="{{old('range_end')}}" class="form-control" placeholder="Range End"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="amount" class="col-md-2 required">Amount</label>
                                        <div class="col-md-8">
                                            <input type="number" name="amount" id="amount" value="{{old('amount')}}" class="form-control" placeholder="Amount"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="appraisalTypes" class="col-md-2">Appraisal Types</label>
                                        <div class="col-md-8">
                                            <select name="appraisalTypes[]" id="appraisalTypes" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                @foreach($apprTypes as $apprType)
                                                    <option value="{{$apprType->id}}">{{$apprType->description}}</option>
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
                                                    <option value="{{$key}}">{{$value}}</option>
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
                                                    <option value="{{$addenda->id}}">{{$addenda->descrip}}</option>
                                                @endforeach
                                            </select>
                                            <p>Select addendas if this proposed loan only applies to certain addendas. Leave unchanged if this will apply to all.</p>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </div>
                            </form>
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
