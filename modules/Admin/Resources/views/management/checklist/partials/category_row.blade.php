<div class="row" id="category_div_sort_{!! $row->id !!}">
    <div class="col-md-12">
        <div class="panel panel-success category_div" id="category_div_{!! $row->id !!}">
            <div class="panel-heading" style='font-size: 16px; font-family: "Helvetica Neue", Helvetica, Arial, sans-serif'>
                <div class="row">
                    <div class="pull-left col-md-10">
                        <h3 class="panel-title">
                            {!! $row->title !!} ( @if(isset($categoriesQuestionsCount[$row->id])) {!! $categoriesQuestionsCount[$row->id] !!} @else 0 @endif )
                        </h3>
                    </div>
                    <div class="pull-right col-md-1">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" style="color: #333">
                                Options <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="color: #333">
                                @if($row->is_active)
                                <li class="option"><a href='{!! route('admin.qc.checklist.update_category_status', [$row->id]) !!}' title='Update Status To Inactive'>Mark Inactive</a></li>
                                @else
                                <li class="option"><a href='{!! route('admin.qc.checklist.update_category_status', [$row->id]) !!}' title='Update Status To Active'>Mark Active</a></li>
                                @endif
                                <li class="option"><a href='{!! route('admin.qc.checklist.edit_category', [$row->id]) !!}' title='Edit Category'>Edit Category</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row category_questions_div" id="category_questions_div_{!! $row->id !!}">
                    <div class="col-md-12">
                        <div id="category_questions_row_{!! $row->id !!}" class="category_questions_row">
                            @include('management.checklist.partials.category_questions')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>