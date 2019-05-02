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
                <div class="col-lg-6">
                    <div class="form-group">
                        {{ Form::label('title', 'Title', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('title', 'title', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_name', 'Company Name', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_name', 'company_name', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_address', 'Company Address', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_address', 'company_address', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_address2', 'Company Address 2', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_address2', 'company_address2', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_city', 'Company City', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_city', 'company_city', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_state', 'Company State', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_state', 'company_state', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('company_zip', 'Company Zip', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_zip', 'company_zip', null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="form-group">
                        {{ Form::label('company_phone', 'Company Phone', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('company_phone', 'company_phone', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('incoming_email', 'Incoming Email', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('incoming_email', 'incoming_email', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('outgoing_email', 'Outgoing Email', ['class' => 'col-lg-3 col-xs-12 required']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('outgoing_email', 'outgoing_email', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('twitter', 'Twitter', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('twitter', 'twitter', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('linkedin', 'LinkedIn', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('linkedin', 'linkedin', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        {{ Form::label('facebook', 'Facebook', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::input('facebook', 'facebook', null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">

                        <div class="col-lg-12 col-xs-12">
                            <div class="i-checks">
                                <label>
                                    {{ Form::checkbox('is_active', '1', (!empty($amc) && $amc->is_active) ? true : false, ['class' => 'form-control']) }} <i></i> Is Active
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        {{ Form::label('emails', 'Emails', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::textarea('emails', (!empty($associations) ? $associations : null), ['class' => 'form-control', 'rows' => '3']) }}
                            <i>
                                Note: Enter user email address that will be associated with this AMC. Enter one email per line. Use * as a domain wildcard.
                                For example: *@domain.com will match all emails under 'domain.com'
                            </i>
                        </div>
                    </div>
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