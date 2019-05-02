<div class="panel-body">
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('title', 'title', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('survey_id', 'Survey', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('survey_id', $surveys, (!empty($survey) ? $survey : null), ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('is_active', 'Active', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('is_active', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('is_required', 'Required', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('is_required', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('type', 'Question Type', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('type', ['yesno' => 'Yes / No', 'textfield' => 'Text Field', 'textarea' => 'Text Area', 'rating' => 'Rating', 'dropdown' => 'Dropdown'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('rating_items', 'Rating Items', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('rating_items', 'rating_items', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
                            <i>Note: Description will be visible to users under the question title.</i>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('extra', 'Extra', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::textarea('extra', null, ['class' => 'form-control', 'rows' => '3']) }}
                            <i>Note: his content is used for the question types 'dropdown' to generate the list. Use one item per line.<br />
                                Example to generate a list of gender:<br />
                                m=Male<br />
                                f=Female<br />
                                u=Unknown</i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="ibox-footer">
                    <button class="btn btn-success pull-right">{{ $button_label }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
