<div class="row">
    <div class="col-md-2">
        <div class="checkbox">
            <label>
                @if ($ticket->closed_date)
                    <input type="checkbox" value="1" name="open" id="open_ticket"> Open Ticket
                @else
                    <input type="checkbox" value="1" name="close" id="close_ticket"> Close Ticket
                @endif
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="checkbox" value="1" name="public" id="public_comment"> Public Log
            </label>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::select('assign', getList($assignments, 'Assign', true, true), $request->assign,
                ['id' => 'assign', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::select('status', getList($statuses->pluck('name', 'id'), 'Status', true), $request->status,
                ['id' => 'status', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::select('category', getList($categories->pluck('name', 'id'), 'Category', true), $request->category,
                ['id' => 'category', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            {!! Form::select('priority', getList($priorities, 'Priority', true), $request->priority,
                ['id' => 'priority', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-3 col-md-offset-2">
        <div class="form-group">
            {!! Form::text('orderid',
                $request->get('orderid', ($ticket->orderid ? ($ticket->type.'-'.$ticket->orderid) : '')),
                ['id' => 'orderid', 'class' => 'form-control order-search', 'placeholder' => 'Assign To Order']
            ) !!}

        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::select('multi', getList($multiMods->pluck('title', 'id'), 'Multi-Moderation'), $request->multi,
                ['id' => 'multi', 'class' => 'form-control']
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
</div>

<hr/>

<div class="row">
    <div class="col-md-1">
        <div class="checkbox">
            <label><input type="checkbox" value="1" name="reply_checkbox" id="reply_checkbox"> Reply</label>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            {!! Form::select('template', getList($emailTemplates, 'Template'), $request->template,
                ['id' => 'template', 'class' => 'form-control']
            ) !!}
        </div>
    </div>
    <div class="col-md-4 hidden reply-hidden">
        <div class="checkbox">
            <label><input type="checkbox" value="1" name="reply_all" id="reply_all"> Reply Additional</label>
        </div>
    </div>

</div>
<div class="row hidden reply-hidden">
    <div class="col-md-7">
        <div class="form-group">
            {!! Form::text('reply_subject', $request->get('reply_subject', $ticket->normalizeSubject),
                ['id' => 'reply_subject', 'class' => 'form-control', 'placeholder' => 'Email Subject']
            ) !!}
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group">
            {!! Form::text('reply_to', $request->get('reply_to', $ticket->from_content),
                ['id' => 'reply_to', 'class' => 'form-control', 'placeholder' => 'Email To Address']
            ) !!}
        </div>
    </div>
</div>
<div class="row hidden reply-all-hidden">
    <div class="col-md-10">
        <div class="form-group">
            {!! Form::textArea('reply_additional',
                $request->get('reply_additional', $ticket->getAdditionalEmails($ticket->cc_content)),
                [
                    'id' => 'reply_additional', 'rows' => 5, 'class' => 'form-control',
                    'placeholder' => 'Additional Email Addresses'
                ]
            ) !!}
        </div>
    </div>
</div>
@if ($ticket->orderid)
    <div class="row hidden reply-hidden">
        <div class="col-md-12">
            <div class="form-group">
                <label class="toggle-attachments">Attachments (Click To View)</label>
            </div>
        </div>
        <div class="col-md-12 hidden" id="attachments-box">
            @include('admin::appraisal._qc_attachments', ['orderId' => $ticket->orderid, 'name' => 'attachments'])
        </div>
    </div>
@endif

<p></p>