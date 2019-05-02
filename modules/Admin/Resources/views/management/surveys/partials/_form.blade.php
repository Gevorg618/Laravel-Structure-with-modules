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
                        {{ Form::label('is_active', 'Default', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('is_active', ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('type', 'Type', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::select('type', ['order' => 'Order', 'user' => 'User'], null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('user_types', 'User Type', ['class' => 'col-lg-3 col-xs-12']) }}
                        <i>Note: Applies only to Survey with type 'User' Selected</i>
                        <div class="col-lg-12 col-xs-12">
                            @foreach($userTypes AS $user)
                                <div class="i-checks">
                                    <label>
                                        {{ Form::checkbox('user_type[]', $user->id, (!empty($survey->connected_user_types) && in_array($user->id, $survey->connected_user_types)) ? true : false, ['class' => 'form-control']) }} <i></i> {{ $user->descrip }}
                                    </label>
                                </div>

                            @endforeach
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('expires_date', 'Date To Expire', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::text('expires_date', old('expires_date', (!empty($survey->expires_date)) ? date('Y-m-d', $survey->expires_date) : ''), ['class' => 'form-control datepicker']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('description', 'Description', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::textarea('description', null, ['class' => 'form-control', 'rows' => '3']) }}
                            <i>Note: Description will be visible to users at the top of the page under the survey title.</i>
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

@push('scripts')
<script type="text/javascript">
    $(document).ready(function(){
        $('.datepicker').datetimepicker({
            format:'YYYY-MM-DD'
        });
    });
</script>
@endpush