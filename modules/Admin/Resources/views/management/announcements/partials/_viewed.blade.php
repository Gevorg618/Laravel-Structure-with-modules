<!-- Modal -->
<div class="modal fade" id="viewedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">{{ count($announcement->viewed) }} Users Viewed</h4>
            </div>
            <div class="modal-body">
                <div class="panel-body panel-body-table">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Full Name</th>
                                <th>Viewed At</th>
                                <th>Email</th>
                                <th>User Type</th>
                                <th>Client</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($announcement->viewed as $user)
                                <tr>
                                    <td>{{ $user->userData->firstname }} {{ $user->userData->lastname }}</td>
                                    <td>{{ toDate($user->pivot->created_date) }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->userType->descrip }}</td>
                                    <td>{{ $user->userData->company }}</td>
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