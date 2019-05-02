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
                        {{ Form::label('name', 'Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('name', 'name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('bgcolor', 'Background Color', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::input('bgcolor', 'bgcolor', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('textcolor', 'Text Color', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {!! Form::input('textcolor', 'textcolor', null, ['class' => 'form-control']) !!}
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