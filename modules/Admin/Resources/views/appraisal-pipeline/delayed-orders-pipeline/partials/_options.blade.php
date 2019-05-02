{{ Form::open( ['route' => ['admin.appraisal-pipeline.delayed-pipeline.delete', 'id' => $row->id], 'class' => 'form-horizontal']) }}
    {{method_field('delete')}}
    <button type="submit" class="btn btn-danger" style="padding: 0px 5px; font-size: 12px;"><i class="fa fa-trash"></i> Delete</button>
{{ Form::close() }}
