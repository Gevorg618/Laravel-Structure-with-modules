<label class="long">Current Background Check Document</label>
<p style="margin-top:5px;" class="user_document_p">
    @if($backgroundDocument && $backgroundLink)
    <a href='{{ $backgroundLink }}' target='_blank'>Download Document</a> ({{ date('m/d/Y H:i', $backgroundDocument->created_date) }})
    @else
    None
    @endif
</p>