<label class="long control-label">Current W-9 Document</label>
<p style="margin-top:5px;" class="user_document_p">
    @if($userW9Document && $userW9DocumentLink)
    <a href='{{ $userW9DocumentLink }}'  target='_blank'>Download W9 Document</a> ({{ date('m/d/Y H:i', $userW9Document->created_date) }})
    @else
    None
    @endif
</p>