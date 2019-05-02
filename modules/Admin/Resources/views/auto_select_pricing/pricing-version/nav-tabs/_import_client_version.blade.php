{{ Form::open([ 'route' => 'admin.autoselect.pricing.versions.import-client-version', 'class' => 'form-group', 'file'=> 'true', 'enctype' => 'multipart/form-data'])}}

    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
                <div class="ibox-content">
                	<div class="panel-body panel-body-table">
                		<h3 class="panel-title" style="margin-bottom: 30px;">Importing Client Version Tool</h3>
                		<div class="form-group">
						    <label for="client" class="col-md-1 control-label">Client</label>
						    <div class="col-md-4">
						    	{{ Form::select('client', $clients, null,  ['class' => 'form-control copy-from' ]) }}	
						     </div>

						    <div class="col-md-3">
						    	<label class="btn btn-warning btn-file">
								    Upload File <input type="file" name="client_file" style="display: none;">
								</label>
						     </div>

						     <div class="col-md-3">
						    	<a href="{{ route('admin.autoselect.pricing.versions.download-template') }}" class="btn btn-success pull-right">Download Template</a>
						     </div>
						</div>
                	</div>
				    <div class="row">
                        <div class="ibox-footer">
                            <button type="submit" class="btn btn-primary pull-right">Import</button>
                        </div>
                    </div>
				</div>
			</div>	
		</div>
	</div>
			
{{ Form::close() }}
   