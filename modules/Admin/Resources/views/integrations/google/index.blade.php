@extends('admin::layouts.master')

@section('title', 'Google API')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Integrations', 'url' => '#'],
        ['title' => 'Google API', 'url' => route('admin.integrations.google')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Grant Auth</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($client->getLog())
                                <div class="alert alert-danger">{{ nl2br($client->getLog()) }}</div>
                            @else
                                @php $token = $client->getToken(); @endphp
                                @if ($token)
                                    @if ($client->isTokenExpired())
                                        <div class="alert alert-warning">Access Token is expired</div>
                                    @endif

                                    @if (isset($token['created']) && isset($token['expires_in']))
                                        <dl class="dl-horizontal">
                                            <dt>Created</dt>
                                            <dd>{{ date('m/d/Y g:i A', $token['created']) }}</dd>
                                            <dt>Expires</dt>
                                            <dd>{{ date('m/d/Y g:i A', $token['created'] + $token['expires_in']) }}</dd>
                                        </dl>
                                    @endif

                                    <a href="{{ route('admin.integrations.google.refresh') }}"
                                       class="btn btn-primary">Refresh</a>
                                    <a href="{{ route('admin.integrations.google.revoke') }}"
                                       class="btn btn-danger">Revoke</a>
                                @else
                                    <a href="{{ $client->getAuthUrl() }}" class="btn btn-primary">Login With Google!</a>
                                @endif
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">Gmail Statistics</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            @if ($client->isClientValid())
                                <dl class="dl-horizontal">
                                    <dt>Received Today</dt>
                                    <dd>{{ number_format($client->countTodayEmails()) }}</dd>
                                    <dt>Tickets Created</dt>
                                    <dd>{{ number_format($ticketsCount) }}</dd>
                                </dl>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12" id="email-account-emails">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Search Emails</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form class="form-inline" role="form">
                                <div class="form-group filters_form">
                                    {!! Form::text('search_string', '', [
                                        'id' => 'search_string',
                                        'class' => 'form-control',
                                        'placeholder' => 'Search Email Account'
                                    ]) !!}

                                    <button type="button" id="search_email_account"
                                            class="btn btn-primary">Search
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                    <hr>
                    <div class="table-responsive" id="emails-table">
                        @if ($client->isClientValid())
                            @include('admin::integrations.google.templates._emails', ['emails' => $client->getEmails()])
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="email_view_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title" id="email_view_modal_title"></h4>
                </div>
                <div class="modal-body" id="email_view_modal_content">
                    <iframe class="container" id="internal" frameborder="0" src=""
                            style="width:100%;overflow-x:auto;overflow-y:auto;"></iframe>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script type="text/javascript" src="{!! asset('js/main.js') !!}"></script>
@endpush