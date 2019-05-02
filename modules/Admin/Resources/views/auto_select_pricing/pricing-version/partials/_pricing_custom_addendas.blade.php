{{ Form::open(  [ 'route' => ['admin.autoselect.pricing.versions.pricing-client-update-addendas', $clientId], 'class' => 'form-horizontal', 'enctype' => 'multipart/form-data']) }}
    <table class="table">
    <tr>
        <th>Title</th>
        <th>Original Amount</th>
        <th>Custom Amount</th>
    </tr>
    @foreach($addendas as $key => $addenda)
        <tr>
            <td>{{ $addenda->descrip}}</td>
            <td>{{ $addenda->price }}</td>
            <td>
                @if (count($savedAddendas) > 0)
                        @foreach($savedAddendas as $addendaId => $amount)
                            @if ($addendaId == $addenda->id) 
                            {{ Form::text('addendas['.$addendaId.']', $amount, ['class' => 'form-control', 'id' => 'addenda_amount', 'data-index' => $addenda->id]) }}   
                            @endif
                        @endforeach
                @else
                    {{ Form::text('addendas['.$addenda->id.']', null, ['class' => 'form-control', 'id' => 'addenda_amount', 'data-index' => $addenda->id]) }}   
                @endif
                                 
            </td>
        </tr>
    @endforeach

    </table>
    <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-primary" > Save </button>
    </div>
{{ Form::close() }}    