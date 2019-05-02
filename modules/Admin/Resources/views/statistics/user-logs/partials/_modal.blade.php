<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ $userLog->dts }} - {{ strip_tags($userLog->email) }}</h4>
            </div>
            <div class="modal-body" style="height:80vh;">
                <iframe src="{{ route('admin.tools.user-logs.iframe',$userLog->id) }}" width="100%" height="100%" frameborder="0"></iframe>
            </div>
        </div>
    </div>
</div>