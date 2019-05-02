<div class="row">
    <div class="col-md-12">
        <h2>Question Information</h2>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="title" class="col-md-2 control-label">Title</label>
            <div class="col-md-10">
                {!! Form::text('title', isset($row) ? old('title', $row->title) :null,
                    ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title']
                ) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="description" class="col-md-2 control-label">Description</label>
            <div class="col-md-10">
                {!! Form::textarea('description', isset($row) ? old('description', $row->description) : null,
                    ['class' => 'form-control', 'placeholder' => 'Description', 'rows' => 3]
                ) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="title" class="col-md-2 control-label">Position</label>
            <div class="col-md-10">
                {!! Form::text('pos', isset($row) ? old('title', $row->position) : null,
                    ['id' => 'position', 'class' => 'form-control', 'placeholder' => 'Position']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="format" class="col-md-2 control-label">Format</label>
            <div class="col-md-10">
                {!! Form::select('format', \App\Models\Appraisal\QC\DataQuestion::getFormats(),
                     isset($row) ? old('format', $row->format) : null,
                    ['id' => 'format', 'class' => 'form-control', 'placeholder' => 'Choose format']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="is_active" class="col-md-2 control-label">Active</label>
            <div class="col-md-10">
                {!! Form::select('is_active', [0 => 'No', 1 => 'Yes'],
                    isset($row) ? old('is_active', $row->is_active) : null,
                    ['id' => 'is_active', 'class' => 'form-control', 'placeholder' => 'Choose activity']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="is_required" class="col-md-2 control-label">Required</label>
            <div class="col-md-10">
                {!! Form::select('is_required', [0 => 'No', 1 => 'Yes'],
                    isset($row) ? old('is_required', $row->is_required) : null,
                    ['id' => 'is_required', 'class' => 'form-control', 'placeholder' => 'Choose required']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="field_type" class="col-md-2 control-label">Field Type</label>
            <div class="col-md-10">
                {!! Form::select('field_type', \App\Models\Appraisal\QC\DataQuestion::getFieldTypes(),
                    isset($row) ? old('field_type', $row->field_type) : null,
                    ['id' => 'field_type', 'class' => 'form-control', 'placeholder' => 'Choose field type']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="title" class="col-md-2 control-label">Field Extra</label>
            <div class="col-md-10">
                {!! Form::textarea('field_extra',
                    isset($row) ? old('field_extra', $row->field_extra) : null,
                    ['id' => 'field_extra', 'class' => 'form-control', 'placeholder' => 'Field Extra']
                ) !!}
                <p>Enter field extra options. Mostly used for the dropdowns/multi/checkboxes/radio buttons etc. One selection per line separate key/value with a = sign. For example:<br>
                    m=Male<br>
                    f=Female<br>
                    u=Unknown<br>
                    Or just values:<br>
                    Male<br>
                    Female<br>
                    Unknown</p>
            </div>
        </div>

        <div class="form-group">
            <label for="default_value" class="col-md-2 control-label">Default Value</label>
            <div class="col-md-10">
                {!! Form::text('default_value',
                    isset($row) ? old('title', $row->default_value) : null,
                    ['id' => 'default_value', 'class' => 'form-control', 'placeholder' => 'Default value']
                ) !!}
            </div>
        </div>

    </div>
    <div class="col-md-6">
        <div class="alert alert-info">
            Select any value to filter by it. if nothing is selected in each filter below then it'll be applied to all appraisals.
        </div>
        <div class="form-group">
            <label for="loan_reason" class="col-md-3 control-label">Loan Reason</label>
            {!! Form::select('loan_reason[]', $loanReasons,
            isset($row) ? $row->loanReasons->pluck('id') : null,
            [
                'id' => 'loan_reason',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
        </div>
        <div class="form-group">
            <label for="loan_type" class="col-md-3 control-label">Loan Type</label>
            {!! Form::select('loan_type[]', $loanTypes,
            isset($row) ? $row->loanTypes->pluck('id') : null,
            [
                'id' => 'loan_type',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
        </div>
        <div class="form-group">
            <label for="appraisal_type" class="col-md-3 control-label">Appraisal Type</label>
            {!! Form::select('appraisal_type[]', $appraisalTypes,
            isset($row) ? $row->appraisalTypes->pluck('id') : null,
            [
                'id' => 'appraisal_type',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
        </div>
        <div class="form-group">
            <label for="clients" class="col-md-3 control-label">Clients</label>
            {!! Form::select('clients[]', $clients,
            isset($row) ? $row->clients->pluck('id') : null,
            [
                'id' => 'clients',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
        </div>
        <div class="form-group">
            <label for="lenders" class="col-md-3 control-label">Lenders</label>
            {!! Form::select('lenders[]', $lenders,
            isset($row) ? $row->lenders->pluck('id') : null,
            [
                'id' => 'lenders',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
        </div>
    </div>
</div>
