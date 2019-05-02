<div class="row" id="category_div_sort_{!! $lenderId !!}">
    <div class="col-md-12">
        <div class="panel panel-success category_div" id="category_div_{!! $lenderId !!}">
            <div class="panel-heading">
                <div class="row">
                    <div class="pull-left col-md-10">
                        <h3 class="panel-title">
                            {!! $lenderDesc !!}
                        </h3>
                    </div>
                    <div class="pull-right col-md-1">
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown" style="color: #333">
                                Options <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu" style="color:#333;">
                                <li class="option"><a href='{!! route('admin.qc.checklist.lender.change_activity', [$lenderId, 1]) !!}'>Mark All Active</a></li>
                                <li class="option"><a href='{!! route('admin.qc.checklist.lender.change_activity', [$lenderId, 0]) !!}'>Mark All Inactive</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body">
                <div class="row category_questions_div" id="category_questions_div_{!! $lenderId !!}">
                    <div class="col-md-12">
                        <div id="category_questions_row_{!! $lenderId !!}" class="category_questions_row">
                            @include('management.checklist.partials.lender_questions')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>