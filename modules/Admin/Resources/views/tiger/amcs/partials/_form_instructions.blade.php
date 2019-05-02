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
                        {{ Form::label('instructions', 'Instructions', ['class' => 'col-lg-3 col-xs-12']) }}
                        <div class="col-lg-12 col-xs-12">
                            {{ Form::textarea('instructions', null, ['class' => 'form-control summernote', 'rows' => '3']) }}
                            <i>
                                Note: Enter special instructions used for specific AMC. These will show up for all Landscape instances order details page for Admins.
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