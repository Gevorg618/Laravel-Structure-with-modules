<button type="reset" class="btn btn-default" id="multi-moderate-tickets">Moderate</button>

<label>Multi Moderation:</label>
{!! Form::select('multimod', getList($multiMods->pluck('title', 'id'), 'Multi-Moderation'), '',
    ['id' => 'multimod', 'class' => 'form-control bootstrap-multiselect-up']
) !!}
