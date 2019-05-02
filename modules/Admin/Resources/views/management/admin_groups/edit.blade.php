@extends('admin::layouts.master')

@section('back_button')
    <a href="{{route('admin.management.admin-groups')}}">
        <i class="fa fa-arrow-circle-left" aria-hidden="true"></i>
    </a>
@endsection

@section('title', 'Viewing Group \'Accounting\'')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Admin Groups', 'url' => route('admin.management.admin-groups')],
        ['title' => 'Viewing Group \'Accounting\'', 'url' => '#']
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
                            <div class="row">
                                <div class="col-lg-3">
                                    <div>
                                        <h4 class="is_protected">Protected: {!!$adminGroup['is_protected'] ? '<span class="protected_yes">Yes</span>' : '<span class="protected_no">No</span>'!!}</h4>
                                    </div>
                                    <ul class="nav nav-tabs nav-stacked">
                                        <li class="active"><a href="#info" data-toggle="tab">Basic Info</a></li>
                                        @foreach($categories as $category)
                                            <li><a href="#{{$category->key}}" data-toggle="tab">{{$category->title}}</a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="col-lg-9">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="info">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <form method="POST" action="{{ route('admin.management.admin-groups.update', ['id' => $adminGroup['id']]) }}">
                                                        {{ csrf_field() }}
                                                        {{method_field('put')}}
                                                        <h4 class="text-info">Basic Information</h4>
                                                        <div class="form-group">
                                                            <label for="title" class="required">Title</label>
                                                            <div>
                                                                <input class="form-control" type="text" name="title" id="title" required="" value="{{$adminGroup->title}}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="color">Color</label>
                                                            <div>
                                                                <input class="form-control" type="text" name="color" id="color" value="{{$adminGroup->color}}">
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="style">Style</label>
                                                            <div>
                                                                <input class="form-control" type="text" name="style" id="style" value="{{$adminGroup->style}}">
                                                            </div>
                                                        </div>
                                                        <hr>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <button type="submit" id="submit" class="btn btn-primary">Save</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                        @foreach($categories as $category)
                                            <div class="tab-pane" id="{{$category->key}}">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <form method="POST" action="{{ route('admin.management.admin-groups.update', ['id' => $adminGroup['id']]) }}" class="form-horizontal">
                                                            <div>
                                                                {{ csrf_field() }}
                                                                {{method_field('put')}}
                                                                <button type="button" class="btn btn-success mark_all_btn mark-yes-tab">Mark All Yes</button>
                                                                <button type="button" class="btn btn-success mark_all_btn mark-no-tab">Mark All No</button>
                                                                @foreach($category->groups as $group)
                                                                    <div>
                                                                        <h4 class="text-info">{{$group->title}}</h4>
                                                                        <button type="button" class="btn btn-info mark_all_btn mark-yes-head">Mark All Yes</button>
                                                                        <button type="button" class="btn btn-info mark_all_btn mark-no-head">Mark All No</button>
                                                                        @foreach($group->items as $item)
                                                                            <div class="form-group">
                                                                                <label class="col-sm-7 items_label">{{$item->title}}</label>
                                                                                <div class="col-sm-3">
                                                                                    <select class="form-control" name="permissions[{{$adminGroup['id']}}][{{$item->key}}]">
                                                                                        <option value="0">No</option>
                                                                                        @if(is_null($saveItem = $savedGroups->where('group_id', $adminGroup['id'])->where('perm_key', $item->key)->first()))
                                                                                            <option value="1" {{$item->default ? 'selected' : ''}}>Yes</option>
                                                                                        @else
                                                                                            <option value="1" {{$saveItem['value'] ? 'selected' : ''}}>Yes</option>
                                                                                        @endif
                                                                                    </select>
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @endforeach
                                                                <hr>
                                                                <div class="row">
                                                                    <div class="col-md-12">
                                                                        <button type="submit" id="submit" class="btn btn-primary">Save</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
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
@push('scripts')
    <link href="{{ masset('css/management/admin_groups/create_edit.css') }}" rel="stylesheet" />
    <script type="text/javascript" src="{{ masset('js/management/admin_groups/edit.js') }}"></script>
@endpush
