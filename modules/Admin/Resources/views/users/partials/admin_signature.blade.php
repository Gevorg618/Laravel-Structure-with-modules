<div class="row">
    <div class="span8">
        <h4>Admin Signature </h4>
        <div class="control-group">
            {!! Form::textarea('email_signature', optional($user->userData)->email_signature, ['class' => 'ckeditor', 'style' => 'width:200px;']) !!}
        </div>
    </div>
</div>
