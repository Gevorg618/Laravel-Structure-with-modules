<div class="row">
    <div class="span9">
        <h4 class="text-info">Special Instructions</h4>
        <div class="control-group" style="margin-bottom: 10px;">
            {!! Form::textarea('user_notes', optional($user->userData)->user_notes, ['class' => 'ckeditor', 'style' => 'width:200px;']) !!}
        </div>
    </div>
</div>
