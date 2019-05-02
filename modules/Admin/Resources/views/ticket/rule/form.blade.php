@extends('admin::layouts.master')
@section('title', 'Ticket Rules - ' . $title)

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Ticket', 'url' => '#'],
        ['title' => 'Rules', 'url' => route('admin.ticket.rule')],
        ['title' => $title, 'url' => '']
    ]
])
@endcomponent

@section('content')

    <div class="row">
        <div class="col-lg-8 col-lg-offset-2 col-md-12">
            <a class="btn btn-sm btn-primary"
               href="{{ route('admin.ticket.rule') }}"><< Back</a>

            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {!! Form::model($row, [
                'route' => ['admin.ticket.rule.' . $action, 'id' => $row->id],
                'id' => 'admin_form',
                'class' => 'form-horizontal'
            ]) !!}

            @if ($row->id)
                {{ method_field('PUT') }}
                {{ Form::hidden('update_id', $row->id) }}
            @endif

            <div class="row">
                <div class="col-md-12">
                    <h2>Rule Information</h2>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="title" class="col-md-2 control-label">Title</label>
                        <div class="col-md-10">
                            {!! Form::text('title', old('title', $row->title),
                                ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="is_active" class="col-md-2 control-label">Active</label>
                        <div class="col-md-10">
                            {!! Form::select('is_active', [0 => 'No', 1 => 'Yes'], old('is_active', $row->is_active),
                                ['id' => 'is_active', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="description" class="col-md-2 control-label">Description</label>
                        <div class="col-md-10">
                            {!! Form::textarea('description', old('description', $row->description),
                                ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => 3]
                            ) !!}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="match_type" class="col-md-2 control-label">Match Type</label>
                        <div class="row" style="margin-top: 5px;">
                            <div class="pull-left" style="padding: 8px 2px 0 0;">if</div>
                            <div class="col-md-2" style="padding: 0 2px 2px 2px;">
                                {!! Form::select('match_type', $matchTypes, old('match_type', $row->match_type),
                                    ['id' => 'match_type', 'class' => 'form-control']
                                ) !!}
                            </div>
                            <div class="col-md-6" style="padding: 8px 0 0 2px;">
                                of the following conditions are met
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <div class="row">
                <div class="col-md-12 m-b-sm">
                    <h2>Conditions</h2>

                    <button type="button" name="add-condition" id="add-condition" value="Add Condition"
                            class="btn btn-primary btn-sm">Add Condition
                    </button>
                </div>

                <div class="col-md-12" id="rule_conditions">
                    <?php $totalConditions = 0; ?>
                    @foreach ($conditions as $condition)
                        <?php $totalConditions++; ?>

                        @include('admin::ticket.rule.partials._condition', [
                            'id' => $totalConditions,
                            'key' => $condition->condition_key,
                            'type' => $condition->condition_match_type,
                            'value' => $condition->condition_value,
                            'conditionKeys' => $conditionKeys,
                            'conditionTypes' => $conditionTypes,
                            'categories' => $categories,
                        ])
                    @endforeach
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h2>Actions</h2>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="close_or_open" class="col-md-2 control-label">Open/Close</label>
                        <div class="col-md-10">
                            {!! Form::select('close_or_open',
                                getList(['open' => 'Open', 'close' => 'Close'], 'Open / Close'),
                                old('close_or_open', $ruleAction->close_or_open),
                                ['id' => 'close_or_open', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="assign_to" class="col-md-2 control-label">Assign</label>
                        <div class="col-md-10">
                            {!! Form::select('assign_to',
                                getList($assignments, 'Assign', true, true),
                                old('assign_to', $ruleAction->assign_to),
                                ['id' => 'assign_to', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="template" class="col-md-2 control-label">Template</label>
                        <div class="col-md-10">
                            {!! Form::select('template', getList($emailTemplates, 'Template'), old('template'),
                                ['id' => 'template', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="public_comment" class="col-md-2 control-label">Public Comment</label>
                        <div class="col-md-10">
                            {!! Form::select('public_comment', [0 => 'No', 1 => 'Yes'],
                                old('public_comment', $ruleAction->public_comment),
                                ['id' => 'public_comment', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reply" class="col-md-2 control-label">Reply</label>
                        <div class="col-md-10">
                            {!! Form::select('reply', [0 => 'No', 1 => 'Yes'],
                                old('reply', $ruleAction->reply), ['id' => 'reply', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="reply_all" class="col-md-2 control-label">Reply All</label>
                        <div class="col-md-10">
                            {!! Form::select('reply_all', [0 => 'No', 1 => 'Yes'],
                                old('reply_all', $ruleAction->reply_all),
                                ['id' => 'reply_all', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>

                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="set_status" class="col-md-2 control-label">Status</label>
                        <div class="col-md-10">
                            {!! Form::select('set_status',
                                getList($statuses->pluck('name', 'id'), 'Status', true),
                                old('set_status', $ruleAction->set_status),
                                ['id' => 'set_status', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="set_category" class="col-md-2 control-label">Category</label>
                        <div class="col-md-10">
                            {!! Form::select('set_category',
                                getList($categories->pluck('name', 'id'), 'Category', true),
                                old('set_category', $ruleAction->set_category),
                                ['id' => 'set_category', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="set_priority" class="col-md-2 control-label">Priority</label>
                        <div class="col-md-10">
                            {!! Form::select('set_priority',
                                getList($priorities, 'Priority', true),
                                old('set_priority', $ruleAction->set_priority),
                                ['id' => 'set_priority', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="assign_order" class="col-md-2 control-label">Assign Order</label>
                        <div class="col-md-10">
                            {!! Form::text('assign_order', old('assign_order', $ruleAction->assign_order),
                                ['class' => 'form-control order-search', 'placeholder' => 'Assign To Order']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="multi_mod" class="col-md-2 control-label">Multi Moderation</label>
                        <div class="col-md-10">
                            {!! Form::select('multi_mod', getList($multiMods->pluck('title', 'id'), 'Multi-Moderation'),
                                old('multi_mod', $ruleAction->multi_mod),
                                ['id' => 'multi_mod', 'class' => 'form-control']
                            ) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="participants" class="col-md-2 control-label">Participants</label>
                        <div class="col-md-10">
                            {!! Form::select('participants[]', $assignments['users'],
                                old('participants', explode(',', $ruleAction->add_participants)),
                                ['multiple' => 'multiple', 'class' => 'form-control bootstrap-multiselect']
                            ) !!}
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">

                    <div class="form-group">
                        <div class="col-md-12">
                            {!! Form::textarea('reply_text', old('reply_text', $ruleAction->comment),
                                ['id' => 'reply_text', 'class' => 'editor order-search']
                            ) !!}
                        </div>
                    </div>

                </div>
            </div>

            <hr>

            <div class="form-group">
                <div class="col-md-10">
                    <button type="submit" value="submit" name="submit" class="btn btn-primary">Save</button>
                </div>
            </div>

            {{ Form::close() }}
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/plugins/bootstrap-multiselect/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/use-multiselect.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/ckeditor.js') }}"></script>
    <script src="{{ masset('js/plugins/ckeditor/adapters/jquery.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket.js') }}"></script>
    <script src="{{ masset('js/ticket/ticket-rules.js') }}"></script>

    <script>
      var $emailTemplate = '{!! $emailTemplate !!}';
      var $conditionRowTemplate = '{!! $conditionRowTemplate !!}';
      var $totalConditions = '{!! $totalConditions !!}';
      var $conditionKeyMatched = '{!! json_encode($conditionKeysMatched) !!}';
    </script>
@endpush