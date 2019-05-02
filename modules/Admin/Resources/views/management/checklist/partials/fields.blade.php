<div class="form-group">
    <label for="title" class="col-md-1 control-label">Title</label>
    <div class="col-md-11">
        {!! Form::textarea('title',
        isset($question) ? old('title', $question->title) : null,
            ['id' => 'title', 'class' => 'form-control', 'placeholder' => 'Title', 'rows' => 3]
        ) !!}
    </div>
</div>
<div class="form-group">
    <label for="qc_correction" class="col-md-1 control-label">QC Correction</label>
    <div class="col-md-11">
        {!! Form::textarea('qc_correction',
        isset($question) ? old('qc_correction', $question->qc_correction) : null,
            ['class' => 'form-control', 'placeholder' => 'QC Correction', 'rows' => 3]
        ) !!}
    </div>
</div>
<div class="form-group">
    <label for="client_correction" class="col-md-1 control-label">Client Correction</label>
    <div class="col-md-11">
        {!! Form::textarea('client_correction',
        isset($question) ? old('client_correction', $question->client_correction) : null,
            ['id' => 'client_correction', 'class' => 'form-control', 'placeholder' => 'Client correction', 'rows' => 3]
        ) !!}
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <label for="category" class="col-md-2 control-label">Category</label>
            <div class="col-md-10">
                {!! Form::select('category', $cats,
                     isset($question) ? old('category', $question->category) : null,
                    ['id' => 'category', 'class' => 'form-control', 'placeholder' => 'Choose category']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="parent_question" class="col-md-2 control-label">Parent Question</label>
            <div class="col-md-10" id="parent_question_div">
                {!! Form::select('parent_question', $parentQuestionsList,
                     isset($question) ? old('parent_question', $question->parent_question) : null,
                    ['id' => 'parent_question', 'class' => 'form-control', 'placeholder' => 'Choose parent question']
                ) !!}
            </div>
        </div>

        <div class="form-group">
            <label for="clients" class="col-md-2 control-label">Clients</label>
            <div class="col-md-10">
                {!! Form::select('clients[]', $clients,
            isset($question) ? $question->clients->pluck('id') : null,
            [
                'id' => 'clients',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
            </div>
        </div>

        <div class="form-group">
            <label for="lenders" class="col-md-2 control-label">Lenders</label>
            <div class="col-md-10">
                {!! Form::select('lenders[]', $lenders,
            isset($question) ? $question->lenders->pluck('id') : null,
            [
                'id' => 'lenders',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
            </div>
        </div>

        <div class="form-group">
            <label for="appraisal_type" class="col-md-2 control-label">Appraisal Type</label>
            <div class="col-md-10">
                {!! Form::select('appraisal_type[]', $appraisalTypes,
            isset($question) ? $question->appraisalTypes->pluck('id') : null,
            [
                'id' => 'appraisal_type',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
            </div>
        </div>


    </div>
    <div class="col-md-6">

        <div class="form-group">
            <label for="realview_rule_id" class="col-md-2 control-label">RealView Rule ID</label>
            <div class="col-md-10">
                {!! Form::text('realview_rule_id',
                isset($question) ? old('realview_rule_id', $question->realview_rule_id) : null,
                    ['id' => 'realview_rule_id', 'class' => 'form-control', 'placeholder' => 'RealView Rule ID']
                ) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="is_active" class="col-md-2 control-label">Active</label>
            <div class="col-md-10">
                {!! Form::select('is_active', [0 => 'No', 1 => 'Yes'],
                    isset($question) ? old('is_active', $question->is_active) : null,
                    ['id' => 'is_active', 'class' => 'form-control', 'placeholder' => 'Choose activity']
                ) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="is_required" class="col-md-2 control-label">Required</label>
            <div class="col-md-10">
                {!! Form::select('is_required', [0 => 'No', 1 => 'Yes'],
                    isset($question) ? old('is_required', $question->is_required) : null,
                    ['id' => 'is_required', 'class' => 'form-control', 'placeholder' => 'Choose required']
                ) !!}
            </div>
        </div>
        <div class="form-group">
            <label for="loan_type" class="col-md-2 control-label">Loan Type</label>
            <div class="col-md-10">
                {!! Form::select('loan_type[]', $loanTypes,
            isset($question) ? $question->loanTypes->pluck('id') : null,
            [
                'id' => 'loan_type',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
            </div>
        </div>
        <div class="form-group">
            <label for="loan_reason" class="col-md-2 control-label">Loan Reason</label>
            <div class="col-md-10">
                {!! Form::select('loan_reason[]', $loanReasons,
            isset($question) ? $question->loanReasons->pluck('id') : null,
            [
                'id' => 'loan_reason',
                'class' => 'form-control bootstrap-multiselect',
                'multiple' => 'multiple'
            ])!!}
            </div>
        </div>
    </div>
</div>
<hr>
<div class="form-group">
    <div class="col-md-10">
        <button type="submit" value="submit" name="submit" class="btn btn-primary">Save</button>
    </div>
</div>