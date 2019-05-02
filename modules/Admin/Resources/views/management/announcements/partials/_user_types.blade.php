<!-- Modal -->
<div class="modal fade" id="userTypesModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">User Types</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body panel-body-table">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Type</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($userTypes as $userType)
                                <tr>
                                    <td>{{ $userType->id }}</td>
                                    <td>{{ $userType->descrip }}</td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>