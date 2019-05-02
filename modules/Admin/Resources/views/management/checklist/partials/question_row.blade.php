<div class="row question_title_div" id="questions_div_elem_{!! $question->id !!}">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <div class="row">
                    <div class="pull-left col-md-10">
                        <div class="question_drag"><span class="glyphicon glyphicon-move"></span></div>
                        <span class="panel-title col-md-10">
				    	{!! $question->title !!}
				    </span>
                    </div>
                    <div class="pull-right col-md-2">
                        <div class="btn-group">
                            <a href="#" class="question-view-div-content btn btn-xs btn-default" id='question-view-div-content-{!! $question->id !!}' title='View Question Information'>Toggle</a>
                            <button type="button" class="btn btn-primary btn-xs dropdown-toggle" data-toggle="dropdown">
                                Options <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu" role="menu">
                                <li class="option"><a href='{!! route('admin.qc.checklist.edit', [$question->id]) !!}' title='Edit Question'>Edit Question</a></li>
                                @if($question->is_active)
                                <li class="option"><a href='{!! route('admin.qc.checklist.update_question_status', [$question->id]) !!}' title='Update Status To Inactice'>Mark Inactive</a></li>
                                @else
                                <li class="option"><a href='{!! route('admin.qc.checklist.update_question_status', [$question->id]) !!}' title='Update Status To Active'>Mark Active</a></li>
                                @endif

                                @if(!$question->is_deleted)
                                <li role="separator" class="divider"></li>
                                <li class="option"><a class="delete-confirm" href='{!! route('admin.qc.checklist.delete', [$question->id]) !!}' title='Delete Question' onclick="return confirm('Are you sure you want to delete?')">Delete</a></li>
                                @endif

                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="panel-body question_div_hidden_content" id="question_div_hidden_content_{!! $question->id !!}" style="display:none;">
                <table class="table">
                    <tr>
                        <td>QC Correction</td>
                        <td colspan="3">{!! $question->qc_correction !!}</td>
                    </tr>
                    <tr>
                        <td>QC Client Correction</td>
                        <td colspan="3">{!! $question->qc_client_correction !!}</td>
                    </tr>
                    <tr>
                        <td>RealView Rule ID</td>
                        <td>{!! $question->realview_rule_id !!}</td>
                        <td>Required</td>
                        <td>@if($question->is_required) Yes @else No @endif</td>
                    </tr>
                    <tr>
                        <td>Loan Type</td>
                        <td>@if(count($question->loanTypes))  {!! implode('<br />', $question->loanTypes->pluck('descrip')->toArray()) !!} @else -- @endif</td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr>
                        <td>Loan Purpose</td>
                        <td>@if(count($question->loanReasons)) {!! implode('<br />', $question->loanReasons->pluck('descrip')->toArray()) !!} @else -- @endif</td>
                        <td>Appraisal Types</td>
                        <td>@if(count($question->appraisalTypes)) {!! implode('<br />', $question->appraisalTypes->map(function ($item, $key) {
                            $item->concat = $item->form . ' - ' . $item->descrip;
                            return $item;
                        })->pluck('concat')->toArray()) !!} @else -- @endif</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

