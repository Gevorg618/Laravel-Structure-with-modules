<label class="long control-label">Current License Document</label>
<p style="margin-top:5px;" class="user_document_p">
    @if($userLicenseDocument && $userLicenseDocumentLink)
    <a href='{{ $userLicenseDocumentLink }}' target='_blank'>Download License Document</a> ({{ date('m/d/Y H:i', $userLicenseDocument->created_date) }})
    @else
    None
    @endif
</p>