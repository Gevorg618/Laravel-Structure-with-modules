<div class="row">
    <div class="span8">
        <h4 class="text-info">Sales Options </h4>
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="control-label">Appraisal Minimum Margin</label>
            {!! Form::text('margin_minimum', $user->margin_minimum, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="control-label">AL Minimum Margin</label>
            {!! Form::text('al_margin_minimum', $user->al_margin_minimum, ['size' => '20', 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="control-label">Appraisal Comission</label>
            {!! Form::text('comission', $user->comission, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

        <div class="control-group" style="margin-bottom: 0px;">
            <label class="control-label">AL Comission</label>
            {!! Form::text('al_comission', $user->al_comission, ['size' => 20, 'class' => 'form-control']) !!}
        </div>

    </div>
</div>