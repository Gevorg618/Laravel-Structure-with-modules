<label class="long">Current E & O Document</label>
<p style="margin-top:5px;" class="user_document_p">
    @if($eandoDocument && $eandoLink)
    <a href='{{ $eandoLink }}' target='_blank'>Download E & O Document</a> ({{ date('m/d/Y H:i', $eandoDocument->created_date) }})
    @else
    None
    @endif
</p>