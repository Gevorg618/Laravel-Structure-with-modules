@foreach($types as $typeId => $type)
    <tr>
        <td  class="col-md-2">{{ $type }}</td>
        <td >
            {{ Form::number('fee_pricing_group['.$state->abbr.']['.$typeId.'][amount]', (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['amount'] : 0, ['class' => 'amount form-control '.$state->abbr.'_all_amount', 'step' =>'0.01' ]) }}
        </td>
        <td>
            {{ Form::number('fee_pricing_group['.$state->abbr.']['.$typeId.'][fhaamount]', (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['fhaamount'] : 0, ['class' => 'fhaamount form-control '.$state->abbr.'_all_fhaamount', 'step' =>'0.01']) }}
        </td>
        <td>
            {{ Form::select('fee_pricing_group['.$state->abbr.']['.$typeId.'][fee_type]', ['' => '-- Select Fee --', 'fixed' => 'Fixes Price', 'deduct' =>'Deduct Amount'],
             (isset($pricingGroupsTypes[$typeId])) ? $pricingGroupsTypes[$typeId]['fee_type'] : null,
              ['class' => 'form-control  '.$state->abbr.'_all_fee_type']) }}
        </td>
    </tr>
@endforeach