<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <tr>
                    <th>ID</th>
                    <th>File Name</th>
                    <th>Size</th>
                    <th>Download</th>
                </tr>

                @if ($files->count())
                    @foreach ($files as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td>{{ $row->filename }}</td>
                            <td>{{ $row->formatFileSize }}</td>
                            <td>
                                <a href="{{ route('admin.ticket.manager.download_document', ['id' => $row->id]) }}"><i
                                            class="fa fa-download fa-lg"></i></a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="4"><i>None Found</i></td>
                    </tr>
                @endif
            </table>
        </div>
    </div>
</div>