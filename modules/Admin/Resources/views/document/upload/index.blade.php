@extends('admin::layouts.master')

@section('title', 'Upload Manager')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Documents', 'url' => '#'],
        ['title' => 'Upload Manager', 'url' => route('admin.document.upload')]
    ]
])
@endcomponent

@section('content')
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-block">
            <button type="button" class="close" data-dismiss="alert">×</button>
            <strong>{{ $message }}</strong>
        </div>
    @endif

    {{-- Upload Form --}}
    @php $bucket = config('filesystems.disks.s3.bucket') @endphp
    @if ($bucket && $bucket != 'your-bucket')
        <div class="row">
            <div class="col-lg-12 m-b-sm">
                <form method="POST" action="{{ route('admin.document.upload.upload') }}"
                      id="upload-form" enctype="multipart/form-data" class="form-inline">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <input type="file" name="file" id="file">
                    </div>

                    <div class="form-group m-r-sm">
                        <label for="bucket">Bucket:</label>
                        <select class="form-control" name="bucket" id="bucket">
                            <option value="{{ $bucket }}" selected>{{ $bucket }}</option>
                        </select>
                    </div>

                    <div class="form-group m-r-lg">
                        <label for="is_public">Is Public:</label>
                        <select class="form-control" name="is_public" id="is_public">
                            <option value="1" selected>Public</option>
                            <option value="0">Private</option>
                        </select>
                    </div>

                    <input type="submit" id="upload" value="Upload File" class="btn btn-primary">
                </form>
            </div>
        </div>
    @else
        <div class="alert alert-danger">
            Please add credentials to access the Amazon S3 storage (config/filesystems.php)
        </div>
    @endif

    {{-- Table with uploaded files --}}
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Bucket</th>
                                    <th>Path</th>
                                    <th>Uploaded At</th>
                                    <th>By</th>
                                    <th>Is Public</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@push('scripts')
    <script src="{{ masset('js/upload-manager.js') }}"></script>
    <script>
      $(function () {
        $app.datatables('#datatable', '{!! route('admin.document.upload.data') !!}', {
          columns: [
            {data: 'name'},
            {data: 'bucket'},
            {data: 'path'},
            {data: 'created_at'},
            {data: 'created_by'},
            {data: 'is_public'},
            {data: 'action'}
          ],
          order: [[0, 'asc']]
        });
      });
    </script>
@endpush

@push('heads')
    <link rel="stylesheet" href="{{ masset('css/upload-manager.css') }}" type="text/css">
@endpush




















