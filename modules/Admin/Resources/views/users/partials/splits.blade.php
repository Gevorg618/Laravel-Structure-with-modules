<br />
<div class="row">
    <div class="span8">
        <div class="control-group" style="margin-bottom: 0px;">
            <label class="long">Enable Zero Fee Invites?</label>
            {!! Form::select('is_zero_fee', $yesNo, $user->is_zero_fee, ['class' => 'form-control']) !!}
        </div>

        <table class="splits_table">
            <tr>
                <th>Appraisal Type</th>
                <th>Conventional</th>
                <th>FHA</th>
            </tr>

            @foreach($apprTypes as $typeId => $typeName)
            <tr>
                <td>{{ $typeName }}</td>
                <td>
                    <div class="input-prepend input-append">
                        <span class="add-on">$</span>
                        {!! Form::text('appr_types['.$typeId.'][conv]', optional($splitValues->get($typeId))->conv, ['class' => 'form-control']) !!}
                        <span class="add-on">.00</span>
                    </div>
                </td>
                <td>
                    <div class="input-prepend input-append">
                        <span class="add-on">$</span>
                        {!! Form::text('appr_types['.$typeId.'][fha]', optional($splitValues->get($typeId))->fha, ['class' => 'form-control']) !!}
                        <span class="add-on">.00</span>
                    </div>
                </td>
            </tr>
            @endforeach

        </table>
    </div>
</div>
