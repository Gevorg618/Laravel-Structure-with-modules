<div class="row" id="panels_div">
    <div class="col-md-12">
        <table id="panels_table" class="table">
            <tr>
                <th>Title</th>
                <th>Date Shown</th>
                <th>Options</th>
            </tr>
            @if($announcementsData)
                @foreach($announcementsData as $announcement)
                    <tr>
                        <td>{{$announcement->title}}</td>
                        <td>{{$announcement->from_date ? date('m/d/Y g:i A', $announcement->from_date) : 'N/A'}}</td>
                        <td>
                            <button data-id="{{$announcement->id}}" class="btn btn-mini btn-default view-announcement">
                                <i class="icon-search"></i> View
                            </button>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="10">No Announcements Found</td>
                </tr>
            @endif
        </table>
    </div>
</div>

<div id="order_index_modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span
                            class="sr-only">Close</span></button>
                <h4 class="modal-title" id="myModalLabel">Announcement</h4>
            </div>
            <div class="modal-body" id="order_index_modal_content">
            </div>
            <div class="modal-footer">
                <button class="btn btn-primary hidden" id="admin_do_continue_announcement" data-id=""
                        data-link=""></button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function () {
            const adminToDoAnnouncement = $('#admin_do_continue_announcement');
            $('.view-announcement').on('click', function () {
                let $id = $(this).attr('data-id');
                const orderIndexModal = $('#order_index_modal');
                $.ajax({
                    url: 'admin/announcement/get/' + $id,
                    method: 'GET',
                    // data: {id: $id}
                }).done(data => {
                    if(data.html) {
                        console.log('ok', data);
                        $('#order_index_modal_content').html(data.html);
                        $(orderIndexModal).modal({backdrop: 'static', keyboard: false}).css({'top': '1%'});

                        $(adminToDoAnnouncement).addClass('hidden');
                        // Set redirect link
                        if(data.row.redirect_link) {
                            $(adminToDoAnnouncement).attr('data-id', data.row.id);
                            $(adminToDoAnnouncement).attr('data-link', data.row.redirect_link);
                            $(adminToDoAnnouncement).html( data.row.redirect_title ? data.row.redirect_title : 'Continue' );
                            $(adminToDoAnnouncement).removeClass('hidden');
                        }

                        $(orderIndexModal).find('.modal-body').css({width:'auto',
                            height:'auto',
                            'max-height': ($(window).outerHeight() - 180) + 'px' });
                    }
                }).fail(err => {
                    throw new Error(err)
                })
            });
            $(adminToDoAnnouncement).on('click', function () {
                window.open($(this).attr('data-link'), '_blank')
            })
        })
    </script>
@endpush