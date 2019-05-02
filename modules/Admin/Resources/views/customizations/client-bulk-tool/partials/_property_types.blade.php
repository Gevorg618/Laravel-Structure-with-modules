<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="alert alert-info">
                            Add or Remove Property Types from One, many or all clients.
                        </div>
                    </div>
                </div>
                <form class="form-horizontal" action="{{route('admin.management.client-bulk-tool.update')}}" method="POST">
                    {{ csrf_field() }}
                    <input type="hidden" name="column" value="show_propertytype">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="clients" class="col-md-2 control-label">Client(s)</label>
                                <div class="col-md-10">
                                    <select name="clients[]" class="form-control bootstrap-multiselect" multiple="multiple">
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}">{{$client->descrip}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">If none are selected it'll apply to all.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="add" class="col-md-2 control-label">Add</label>
                                <div class="col-md-10">
                                    <select name="add[]" class="form-control bootstrap-multiselect" multiple="multiple">
                                        @foreach($properties as $property)
                                            <option value="{{$property->id}}">{{$property->descrip}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">If none are selected it'll ignore this option.</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="remove" class="col-md-2 control-label">Remove</label>
                                <div class="col-md-10">
                                    <select name="remove[]" class="form-control bootstrap-multiselect" multiple="multiple">
                                        @foreach($properties as $property)
                                            <option value="{{$property->id}}">{{$property->descrip}}</option>
                                        @endforeach
                                    </select>
                                    <span class="help-block">If none are selected it'll ignore this option.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary pull-right">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>
