<div class="row">
    <div class="col-md-12">
        <form id="multi-ticket-mod-form" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="checked_tickets" value="{{ implode(',', $checked) }}">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('open_or_close',
                            getList(['open' => 'Open', 'close' => 'Close'], 'Open / Close'),
                            $request->open_or_close,
                            ['id' => 'open_or_close', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('assign', getList($assignments, 'Assign', true, true), $request->assign,
                            ['id' => 'assign', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('status', getList($statuses->pluck('name', 'id'), 'Status', true), $request->status,
                            ['id' => 'status', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('category', getList($categories->pluck('name', 'id'), 'Category', true), $request->category,
                            ['id' => 'category', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::text('orderid', $request->orderid,
                            ['id' => 'orderid', 'class' => 'form-control order-search', 'placeholder' => 'Assign To Order']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('priority', getList($priorities, 'Priority', true), $request->priority,
                            ['id' => 'priority', 'class' => 'form-control']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::select('participants[]', $assignments['users'], $request->participants,
                            ['id' => 'participants', 'multiple' => 'multiple', 'class' => 'form-control bootstrap-multiselect']
                        ) !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="checkbox">
                        <label>
                            <input type="checkbox" value="1" name="public"> Public Log
                        </label>
                    </div>
                </div>
            </div>

            <hr/>

            <div class="row">
                <div class="col-md-12">
                    {!! Form::textArea('reply_text', $request->reply_text,
                        ['id' => 'reply_text', 'class' => 'editor order-search']
                    ) !!}
                </div>
            </div>
        </form>
    </div>
</div>