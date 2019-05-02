@if(!empty($errors->first()))
    <div class="col-md-12" style="margin-top: 15px;">
        <div class="row col-md-6">
            <div class="alert alert-danger alert-dismissible" role="alert">
                 <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <span>{{ $errors->first() }}</span>
            </div>
        </div>
    </div>
@endif
<div class="row margin_top">
    <div class="col-md-6">
        <div class="form-group form_style">
            <label for="team_title" class="col-md-3 control-label">Title</label>
            <div class="col-lg-12 col-xs-12">
                <input id="team_title" type="text" name="team_title" class="form-control" value="{{is_null($adminTeam) ? '' : $adminTeam->team_title}}" placeholder="Title">
            </div>
        </div>
        <div class="form-group form_style">
            <label for="team_key" class="col-md-3 control-label">Key</label>
            <div class="col-lg-12 col-xs-12">
                <input id="team_key" type="text" name="team_key" class="form-control" value="{{is_null($adminTeam) ? '' : $adminTeam->team_key}}" placeholder="Key">
            </div>
        </div>
        <div class="form-group form_style">
            <label for="team_type" class="col-md-3 control-label">Type</label>
            <div class="col-lg-12 col-xs-12">
                <select id="team_type" name="type" class="form-control">
                    @foreach($adminTypes as $key => $value)
                        <option value="{{$key}}" {{is_null($adminTeam) ? '' : $adminTeam->team_type == $key ? 'selected' : ''}}>{{$value}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="form-group form_style">
            <label for="is_active" class="col-md-3 control-label">Active</label>
            <div class="col-lg-12 col-xs-12">
                <select id="is_active" name="is_active" class="form-control">
                    <option value="0">No</option>
                    <option value="1" {{is_null($adminTeam) ? '' : $adminTeam->is_active ? 'selected' : ''}}>Yes</option>
                </select>
            </div>
        </div>
        <div class="form-group form_style">
            <label for="supervisor" class="col-md-3 control-label">Team Supervisor</label>
            <div class="col-lg-12 col-xs-12">
                <input id="supervisor" type="text" name="supervisor" class="form-control" value="{{is_null($adminTeam) ? '' : $adminTeam->supervisor}}" placeholder="Team Supervisor">
            </div>
        </div>
    </div>
    <div class="col-md-5">
        <div class="form-group form_style">
            <label for="phone" class="col-md-3 control-label">Phone</label>
            <div class="col-lg-12 col-xs-12">
                <div class="col-md-6">
                    <div class="row">
                        <input id="phone" type="text" name="phone" class="form-control bfh-phone" data-format="(ddd) ddd-dddd" value="{{is_null($adminTeam) ? '' : $adminTeam->team_phone}}" placeholder="Phone">
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group form_style">
            <label for="cap" class="col-md-3 control-label">Order Cap</label>
            <div class="col-lg-12 col-xs-12">
                <input id="cap" type="number" name="cap" class="form-control number" value="{{is_null($adminTeam) ? '' : $adminTeam->team_cap}}" placeholder="Order Cap">
            </div>
        </div>
        <div class="form-group">
            <label for="qc_uw_pipeline_color" class="col-md-3 control-label color_label">QC/UW Pipeline Color</label>
            <div class="col-md-9 color_popup">
                <div class="alert alert-info">Click and select the color that all orders associated with this team will be highlighted with the color on the QC and UW pipeline.</div>
                <div class="col-md-4">
                    <input id="qc_uw_pipeline_color" type="text" name="color" class="form-control color-picker colorpicker-element" value="{{is_null($adminTeam) ? '' : $adminTeam->qc_uw_pipeline_color}}" placeholder = "Select Color">
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script type="text/javascript" src="{{ masset('js/plugins/colorpicker/bootstrap-colorpicker.min.js') }}"></script>
    <link rel="stylesheet" type="text/css" href="{{ masset('css/plugins/colorpicker/bootstrap-colorpicker.min.css') }}">
    <script type="text/javascript">
        $(document).ready(function(){
            $('.color-picker').colorpicker();
        });
    </script>
@endpush
