@extends('admin::layouts.master')

@section('title', 'Settings Manager')

@component('admin::layouts.partials._breadcrumbs', [
  'crumbs' => [
    ['title' => 'Tools', 'url' => '#'],
    ['title' => 'Settings Manager', 'url' => route('admin.tools.settings')]
  ],
  'actions' => [
    ['title' => 'Add Category', 'url' => route('admin.tools.settings.category.create')],
    ['title' => 'Add Setting', 'url' => route('admin.tools.settings')]
  ]
])
@endcomponent
@section('content')
<div class="row">
  <div class="col-lg-12">
    <div class="ibox float-e-margins">
      <div class="ibox-content">
        <div class="panel-body panel-body-table">
          <div class="table-responsive">
            <table class="table table-striped table-bordered table-hover" id="datatable">
              <thead>
                <tr>
                  <th>Title</th>
                  <th>Key</th>
                  <th>Settings</th>
                  <th>Description</th>
                  <th>Actions</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

@push('scripts')
<script>
$(function() {
    $app.datatables('#datatable', '{!! route('admin.tools.settings.category.data') !!}', {
      columns: [
        { data: 'title', name: 'setting_category.title' },
        { data: 'key', name: 'setting_category.key' },
        { data: 'settings', name: 'settings', searchable: false },
        { data: 'description', name: 'setting_category.description' },
        { data: 'action', name: 'action', orderable: false, searchable: false}
      ],
      order : [ [ 0, 'asc' ] ]
    });
  });
</script>
@endpush