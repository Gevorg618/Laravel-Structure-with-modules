<table class="table table-condensed">
    <tr>
        <th>Name</th>
        <th>Type</th>
        <th>Uploaded By</th>
        <th>Uploaded Date</th>
        <th>Options</th>
    </tr>
    @if($additionalDocuments)
        @foreach($additionalDocuments as $document)
            <tr>
                <td>{{ mb_strimwidth($document->name, 0, 40, "...") }}</td>
                <td>{{ $document->document_type }}</td>
                <td>{{ $document->uploaded_by }}</td>
                <td>{{ date('m/d/Y g:i A', $document->created_date) }}</td>
                <td>
                    <a href='{{ route('admin.users.document-download', [$document->id, 'additional']) }}'
                       rel='tooltip_download' title='Download File' target='_blank'>
                        <img src='/images/icons/famfamfam/database_link.png' alt='Download'/>
                    </a>
                    <a href='{{ route('admin.users.document-view', [$document->id]) }}'
                       rel='tooltip_download' title='View File' target='_blank'>
                        <img src='/images/icons/famfamfam/magnifier.png' alt='View'/>
                    </a>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="5">No Documents Uploaded.</td>
        </tr>
    @endif
</table>
