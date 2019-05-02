<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
        <li><a href="{{ route('admin.ticket.categories.edit', ['id' => $row->id]) }}"><i class="fa fa-edit"></i> Edit</a></li>
        @if(!$row->is_protected)
            <li>
            	{{ Form::open( ['route' => ['admin.ticket.categories.delete', 'id' => $row->id], 'class' => 'form-horizontal']) }}
	            	{{method_field('delete')}}
	            	<button type="submit" class="link-style"><i class="fa fa-trash"></i> Delete</button>
	            {{ Form::close() }}
            </li>
        @endif
    </ul>
</div>

<style type="text/css">
	.link-style {
	    line-height: 25px;
	    margin: 4px;
	    text-align: left;
	    border: none;
	    background: none;
	    width: 100%; 
	    padding: 3px 20px;
	    line-height: 1.42857143;
	}
	.link-style:hover {
	    color: #262626;
	    background-color: #f5f5f5;
	}
</style>