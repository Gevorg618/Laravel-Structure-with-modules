<a class="btn btn-info btn-xs" href="{{ route('admin.frontend-site.latest-news.edit', ['id' => $row->id]) }}"><i class="fa fa-edit"></i> Edit</a>
<a class="btn btn-danger btn-xs" href="{{ route('admin.frontend-site.latest-news.delete', ['id' => $row->id, '_token' => csrf_token()]) }}"><i class="fa fa-trash"></i> Delete</a>