<div class="form-body">
    <div class="row">
        <div class="container">
            @if($clientId != 'default')
            <div class="form-group col-md-12">
                <label name="client" class="control-label col-lg-3 col-xs-12">Client
                </label>
                <div class="col-lg-12 col-xs-12">
                    {{ Form::select('client_id', $clients, $clientId, ['class' => 'form-control selectpicker']) }}
                    <span class="help-block client-error-block"></span>
                </div>
            </div>
            @endif
        	<div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        @if(isset($turnTimes))
                                @foreach($turnTimes as $turnTime)
                                <div class="form-group">
                                    <label name="client" class="col-lg-12 col-xs-12">{{ $turnTime->types->form }} - {{ $turnTime->types->descrip }}</label>
                                    <div class="col-lg-12 col-xs-12">
                                        {{ Form::text('types['.$turnTime->types->id.']', $turnTime->turn_time, ['class' => 'form-control', 'placeholder' => 'Turn Time']) }}
                                        <span class="help-block client-error-block"></span>
                                    </div>
                                </div>
                                @endforeach
                        @else
                            @foreach($types as $type)
                            <div class="form-group">
                                <label name="client" class="col-lg-3 col-xs-12">{{ $type['form'] }} - {{ $type['descrip'] }}
                                </label>
                                <div class="col-lg-12 col-xs-12">
                                    {{ Form::text('types['.$type['id'].']', null, ['class' => 'form-control', 'placeholder' => 'Turn Time']) }}
                                    <span class="help-block client-error-block"></span>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="ibox-footer">
        <button type="submit" class="btn btn-success pull-left">{{ $button_label }}</button>
    </div>
</div>