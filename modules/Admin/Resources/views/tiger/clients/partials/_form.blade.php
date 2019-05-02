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
        <div class="col-md-6">
            <div class="row">
                <div class="col-lg-12">
                    <div class="form-group">
                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('title', 'title', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('domain', 'Domain', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('domain', 'domain', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="row">
                <div class="col-lg-12">
                    @foreach($clientOptions AS $option)
                    <div class="form-group">
                        {{ Form::label($option->key, $option->title, ['class' => 'col-lg-12 col-xs-12']) }}

                        <div class="col-lg-12 col-xs-12">
                            @if($option->field_type == 'yesno')
                            <div class="i-checks">
                                {{ Form::radio('options['.$option->id.']', 0, (!empty($client->options[$option->id]) ? false : true) ) }} <i></i> No
                                {{ Form::radio('options['.$option->id.']', 1, (!empty($client->options[$option->id]) ? true : false) ) }} <i></i> Yes

                            </div>
                            @elseif($option->field_type == 'dropdown')
                                {{ Form::select('options['.$option->id.']', ['' => 'Please select', 'assign' => 'Assign', 'completed' => 'Completed'], (!empty($client->options[$option->id]) ? $client->options[$option->id] : (!empty($client) ? null : $option->default_value)), ['class' => 'form-control']) }}
                            @else
                                {{ Form::text('options['.$option->id.']', (!empty($client->options[$option->id]) ? $client->options[$option->id] : (!empty($client) ? null : $option->default_value)), ['class' => 'form-control']) }}
                            @endif
                                <span id="helpBlock" class="help-block">{{ $option->description }}</span>
                        </div>

                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
                <button class="btn btn-success">{{ $button_label }}</button>

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