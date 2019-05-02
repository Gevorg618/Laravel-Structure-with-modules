<div class="row">
    <div class="col-lg-12">
        <div class="panel panel-warning">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-lg-12">
                        <h3 class="panel-title">Review Options</h3>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                <div class="col-lg-2">Assigned To</div>
                <div class="col-lg-2">

                    <select name="uw_assigned_to" id="uw_assigned_to" class="form-control">
                        @if($order->uw_assigned_to === null || !$order->uw_assigned_to)
                            <option value="" selected="selected">-- Select --</option>
                        @endif
                        @foreach($admins as $id => $admin)
                            <option value="{{$id}}" {{$order->uw_assigned_to === $id ? 'selected="selected"' : ''}}>{{$admin}}</option>
                        @endforeach
                    </select>
                </div>
                </div>
            </div>
        </div>
    </div>
</div>
