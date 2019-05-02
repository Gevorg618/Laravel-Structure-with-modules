<div class="col-md-10">
    <h2>Upload Documents</h2>
    <div class="form-group row">
        <label for="file_upload_name" class="col-md-4">Company Name</label>
        <div class="col-md-4">
            <input type="text" name="file_upload_name" id="file_upload_name"
                   value=""
                   class="form-control">
        </div>
    </div>

    <div class="form-group row">
        <label for="file" class="col-md-4">Company Name</label>
        <div class="col-md-4">
            <input id="file" type="file" name="file" accept=".pdf">
        </div>
    </div>

    <div class="col-md-12" style="text-align: center; margin-top:80px;">
        <button type="submit" class="btn btn-success">Upload</button>
    </div>
    @if(count($client->userGroupFiles))
        <h2>Group Documents</h2>
        <div class="container">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>Uploaded Date</th>
                    <th>Uploaded By</th>
                    <th>Name</th>
                    <th>Download</th>
                    <th>Delete</th>
                </tr>
                </thead>
                <tbody>
                @foreach($client->userGroupFiles as $item)
                    <tr>
                        <td>{{$item->file_location}}</td>
                        <td>{{getUserFullNameById($item->created_by)}}</td>
                        <td>{{$item->docname}}</td>
                        <td>
                            <a href='{{route('admin.management.client.download', ['id' => $item->id])}}'>
                                <i class="fa fa-download"></i>
                            </a>
                        </td>
                        <td>
                            <a href='{{route('admin.management.client.file.delete', ['id' => $item->id])}}'>
                                <i class="fa fa-trash" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>



