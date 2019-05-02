 {{ Form::open(['class' => 'form-group', 'id' => 'addenda_view', 'enctype' => 'multipart/form-data'])}}
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
                @if (count($pricing->addendas) > 0)
                        @foreach($pricing->addendas as $pricingAddenda)
                            @if ($pricingAddenda->pivot->addenda_id == $addenda->id) 
                            {{ Form::text('amounts[]', $pricingAddenda->pivot->amount, ['class' => 'form-control', 'id' => 'addenda_amount', 'data-index' => $addenda->id]) }}   
                            @endif
                        @endforeach
                @else
                    {{ Form::text('amounts[]', null, ['class' => 'form-control', 'id' => 'addenda_amount', 'data-index' => $addenda->id]) }}   
                @endif
                                 
            </td>
        </tr>
    @endforeach

    </table>
{{ Form::close() }}    