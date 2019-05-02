@extends('admin::layouts.master')

@section('title', 'Emails Sent')

@component('admin::layouts.partials._breadcrumbs', [
'crumbs' => [
  ['title' => 'Tools', 'url' => '#'],
  ['title' => 'Emails Sent', 'url' => route('admin.tools.emails-sent')]
]
])
@endcomponent
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover" id="datatable">
                                <thead>
                                <tr>
                                    <th>Subject</th>
                                    <th>From</th>
                                    <th>To</th>
                                    <th>CC</th>
                                    <th>Date</th>
                                    <th>Read</th>
                                </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="modal"></div>
@stop

@push('scripts')
<script type="text/javascript">
    $(function () {
        $app.datatables('#datatable', '{!! route('admin.tools.emails-sent.data') !!}', {
            columns: [
                {data: 'subject',width:300},
                {data: 'from_email'},
                {data: 'to_email'},
                {data: 'cc_email'},
                {data: 'date_human'},
                {data: 'is_read'}
            ],
            order: [[0, 'asc']]
        });
    });
    $(document).ready(function () {
        $(document).on('click','.get-email-body',function(e){
            e.preventDefault();
            var _token = '{{ csrf_token() }}';
            var id = $(this).data('id');
            $.ajax({
                type:'POST',
                url:'{{ route('admin.tools.emails-sent.email-body') }}',
                data:{_token:_token,id:id},
                success:function(data){
                    $('#modal').html(data);
                    $('#myModal').modal();
                }
            });
        });
    });
</script>
@endpush

