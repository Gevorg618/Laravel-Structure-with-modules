@extends('admin::layouts.master')

@section('title', 'Appraisal UW Pipeline')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => $order->address. ' - #'. $order->id, 'url' => '#']
],
'actions' => [
  ['title' => 'Back To Order', 'url' => '#'],
  ['title' => 'Final Report', 'url' => '#'],
]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover">
                                <tr>
                                    <th>Placed</th>
                                    <th>Completed</th>
                                    <th>User</th>
                                    <th>Client</th>
                                    <th>Appraiser</th>
                                    <th>Appraisal Type</th>
                                    <th>Loan Type</th>
                                </tr>
                                <tr>
                                    <td>{{date('m/d/Y H:i', strtotime($order->ordereddate))}}</td>
                                    <td>{{date('m/d/Y H:i', strtotime($order->completed))}}</td>
                                    <td>{{getUserFullNameById($order->orderedby)}}</td>
                                    <td>{{$order->group_descrip}}</td>
                                    <td>{{ $order->appr_name}}</td>
                                    <td>{{$order->type_name}}</td>
                                    <td>{{$order->loan_type}}</td>
                                </tr>
                            </table>
                        </div>
                        <form action="{{route('admin.post-completion-pipelines.appr-uw-pipeline.save-conditions', ['id' => $order->id])}}"
                              role="form" class="form-horizontal" method="POST" id="uw_form">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Previous Conditions</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table">
                                                <tr>
                                                    <th>Date Received</th>
                                                    <th>Category</th>
                                                    <th>Condition</th>
                                                </tr>
                                                @if($previousConditions)
                                                    @foreach($previousConditions as $previousCondition)
                                                        <tr>
                                                            <td>{{date('m/d/Y g:i A', $previousCondition->created_date)}}</td>
                                                            <td>{{$previousCondition->category}}</td>
                                                            <td>{{$previousCondition->cond}}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Clients</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table tabl-hover table-striped client-rows-table">
                                                <tr>
                                                    <th>Client Name</th>
                                                    <th>Client Email</th>
                                                    <th>Remove</th>
                                                    @if($record)
                                                        @if($contacts)
                                                            @foreach($contacts as $contact)
                                                                @include('admin::post-completion-pipelines.appr-uw-pipeline.partials._contact', ['i' => $contact->id, 'name' => $contact->name, 'email' => $contact->email])
                                                            @endforeach
                                                        @endif
                                                    @endif
                                                </tr>

                                            </table>
                                            <button type="button" class="add-client btn btn-primary btn-xs">Add Client
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-primary">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">UW Conditions</h3>
                                        </div>
                                        <div class="panel-body">
                                            <table class="table tabl-hover table-striped condition-rows-table">
                                                <tr>
                                                    <th>Condition</th>
                                                    <th>Category</th>
                                                    <th>Response</th>
                                                    <th>Submitted By</th>
                                                    <th>Remove</th>
                                                </tr>
                                                @if($record)
                                                    @foreach($conditions as $condition)
                                                        @include('admin::post-completion-pipelines.appr-uw-pipeline.partials._condition', ['i' => $condition->id, 'condition' => $condition->cond, 'response' => $condition->response, 'category' => $condition->category, 'name' => getUserFullNameById($condition->created_by)])
                                                    @endforeach
                                                @endif
                                            </table>
                                            <button type="button" class="add-condition btn btn-primary btn-xs">Add
                                                Condition
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="panel panel-danger">
                                        <div class="panel-heading">
                                            <h3 class="panel-title">Operations To Perform</h3>
                                        </div>
                                        <div class="panel-body">
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="send_to_appr" name="send_to_appr" value="1">
                                                Email Appraiser
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" id="send_to_client" name="send_to_client"
                                                       value="1"> Email Client
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="send_support_emails"
                                                       value="1" {{$record && $record->send_support_emails ? 'checked="checked"' : '' }}>
                                                Send Support Emails
                                            </label>
                                            <label class="checkbox-inline">
                                                <input type="checkbox" name="send_final_report_emails"
                                                       value="1" {{$record && $record->send_final_report_emails ? 'checked="checked"' : ''}}>
                                                Send Final Report Emails
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-checklist-option-send-back form-option-checklist hidden">
                                <div class="col-md-12">
                                    <div class="panel panel-default">
                                        <div class="panel-heading">
                                            <div class="row">
                                                <div class="col-md-10 pull-left">
                                                    <h3 class="panel-title">Send Back To Appraiser</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="appraiser_email" class="col-md-4 control-label">Appraiser
                                                            Email</label>
                                                        <div class="col-md-8">
                                                            <input type="text" name="email_name" id="appraiser_name"
                                                                   value="{{$order->appr_name}}" class="form-control"
                                                                   placeholder="Appraiser Name">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="appraiser_name" class="col-md-4 control-label">Appraiser
                                                            Name</label>
                                                        <div class="col-md-8">
                                                            <input type="email" name="email_email" id="appraiser_email"
                                                                   value="{{$order->appr_email}}"
                                                                   placeholder="Appraiser Email" class="form-control">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br/>
                                                    <div class="form-group">
                                                        <label for="appraiser_subject" class="col-md-2 control-label">Subject</label>
                                                        <div class="col-md-10">
                                                            <input type="text" name="email_subject" id="email_subject"
                                                                   value="UW Conditions - {{$order->id}} - {{$order->propaddress1}}"
                                                                   class="form-control"
                                                                   placeholder="Appraiser Subject Line">

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <b>Attachments</b>
                                                </div>
                                                <div class="col-md-12">
                                                    @include('admin::appraisal._qc_attachments', ['orderId' => $order->id, 'orderFiles' => $orderFiles, 'name' => 'appraiser_attach', 'attacheApprasierFiles' => true])
                                                    <hr/>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                <textarea name="email_message" id="email_message" class="editor">{{$appraiserTemplate = convertOrderKeysToValues($apprEmailContent, $order)}}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <hr/>
                                    <div class="form-group">
                                        <div class="col-md-12">
                                            <button type="submit" name="save_conditions" value='Save Conditions'
                                                    class="btn btn-primary">
                                                Save Conditions
                                            </button>
                                            @if($isTeamLead)
                                                <button type="button" name="remove-all-conditions"
                                                        id="remove-all-conditions"
                                                        value='Remove All Conditions' class="btn btn-primary">Remove All
                                                    Conditions
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12"></div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@push('scripts')
    <script>
        var $totalClients = @php echo (isset($contacts) && $contacts) ? count($contacts) : 0 @endphp;
        var $totalConditions = @php echo (isset($conditions) && $conditions) ? count($conditions) : 0; @endphp;
        var $editorTemplate = @php echo json_encode($appraiserTemplate); @endphp;
        var $orderId = @php echo intval($order->id); @endphp;
        var $inEditMode = @php echo $record ? 'true' : 'false'; @endphp;
        var $ignoredCategories = @php echo json_encode($UWIgnored) @endphp;
        var $UWCategories = @php echo json_encode($uwCategories) @endphp;
    </script>
    <script src="{{ masset('js/appraisal/appr_uw_pipeline/uw_conditions.js')}}"></script>
@endpush
