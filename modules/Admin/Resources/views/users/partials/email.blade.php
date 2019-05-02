<div class="row">
    <div class="span10">
        <h4 class="text-info">Send Email</h4>

        <div class="control-group" style="margin-bottom: 5px;">
            <label class="control-label">Subject</label>
            {!! Form::text('email_subject', null, ['class' => 'form-control', 'id' => 'email_subject']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0;">
            <label class="control-label" id="email_template_label">Template </label>
            {!! Form::select('email_template', $emailTemplates, null, ['class' => 'form-control', 'placeholder' => 'Choose an email template', 'id' => 'email_template']) !!}
            <span id="email_template_loading"></span>
        </div>

        <div class="control-group" style="margin-bottom: 10px;">
            {!! Form::textarea('email_content', null, ['class' => 'ckeditor']) !!}
        </div>

        <div class="row offset3">
            <div class="span3">
                <button type="button" id="send-email" name="send-email" class="btn btn-primary">Send Email</button>
            </div>
        </div>

    </div>
</div>


