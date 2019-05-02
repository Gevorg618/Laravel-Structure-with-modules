@extends('admin::layouts.master')

@section('title', 'User Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'User Manager', 'url' => route('admin.users.index')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    @if (count($errors) > 0)
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="panel-body">
                        <h1>User Manager</h1>
                        <div class="pull-left col-md-5">
                            {!! Form::open([
                                'route' => ['admin.users.index'],
                                'id' => 'users_form',
                                'class' => 'form-horizontal',
                                'method' => 'GET'
                            ]) !!}
                            <div class="form-group">
                                <label for="user_type" class="col-md-2 control-label">User Type</label>
                                <div class="col-md-6">
                                    {!! Form::select('user_type', $userTypes, Request::get('user_type'),
                                        ['id' => 'type', 'class' => 'form-control', 'placeholder' => 'User Type']
                                    ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="state" class="col-md-2 control-label">State</label>
                                <div class="col-md-6">
                                    {!! Form::select('state', getStates(), Request::get('state'),
                                        ['id' => 'type', 'class' => 'form-control', 'placeholder' => 'State']
                                    ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="is_priority" class="col-md-2 control-label">Is Priority</label>
                                <div class="col-md-6">
                                    {!! Form::select('is_priority', ['no' => 'No', 'yes' => 'Yes'], Request::get('is_priority'),
                                        ['id' => 'type', 'class' => 'form-control', 'placeholder' => 'Is priority']
                                    ) !!}
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="search" class="col-md-2 control-label">Search</label>
                                <div class="col-md-6">
                                    {!! Form::text('search', Request::get('search'),
                                        ['id' => 'search', 'class' => 'form-control', 'placeholder' => 'Search']
                                    ) !!}
                                </div>
                            </div>

                            <div class="clear"></div>


                            <div class="form-group row">
                                <div class="col-md-10">
                                    <button type="reset" class="btn btn-primary">Reset Filters</button>
                                    <button type="submit" value="show_users" id="show_users"
                                            class="btn btn-primary">Show users
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <div class="clear"></div>
                        </div>
                    </div>
                        <div class="table-responsive" id="show_users_div">
                            <table width="100%" class="table table-striped table-hover" id="users_table">
                                <thead>
                                <tr>
                                    <th>&nbsp;</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Mobile</th>
                                    <th>Type</th>
                                    <th>Company</th>
                                    <th><a href='javascript:void(0);' rel='tooltip' title='Placed / Accepted / Completed'>P / A / C</a></th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($rows as $user)
                                        <tr class="@if($user->active != 'Y') danger @endif">
                                            <td><img src="{{ $user->getAvatar() }}" class="img-rounded" style="height: 36px; width: 36px"></td>
                                            <td><a href="{{ route('admin.users.show', [$user->id]) }}">{{ $user->userData->fullname}}</a><br>{{ $user->email }}</td>
                                            <td>{{ $user->userData->phone }}</td>
                                            <td>{{ $user->userData->mobile }}</td>
                                            <td>{{ $user->userType->descrip }}</td>
                                            <td>{{ optional($user->userGroups)->company }} {{ optional($user->userGroups)->address1 }}</td>
                                            <td>
                                                @if($user->user_type == 4) - / {{ $user->getOrdersAccepted() }} / {{ $user->getApprOrdersCompleted() }}
                                                @elseif($user->user_type == 14)  - / {{ $user->getALOrdersAccepted() }} / {{ $user->getALOrdersCompleted() }}
                                                @elseif($user->user_type == 5) {{ $user->getOrdersPlaced() }} / - / {{ $user->getOrdersCompleted() }}
                                                @else - / - / - @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {{ $rows->appends(Request::all())->links() }}
                        </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
@endpush
