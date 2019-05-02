<div class="row checklist-section-row">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-md-11 pull-left">
                        <h3 class="panel-title">Conditions Requested on {{date('m/d/Y', $record->created_date)}}</h3>
                    </div>
                    <div class="col-md-1 pull-right"></div>
                </div>
            </div>
            <div class="panel-body">
                <table class="table table-striped">
                    <tr>
                        <th class="col-md-8">Title</th>
                        <th class="col-md-2">Action Required</th>
                    </tr>
                    @foreach($pendingConditions as $rule)
                        @php
                            $current = '-';
                            $location = !is_null(Request::input('rulesconditions.'.$rule->id)) ? Request::input('rulesconditions.'.$rule->id) : $rule->location;
                            if($rule->answer) {
                                $current = $rule->answer;
                            }
                            $selected = $current;
                        @endphp
                        <tr class="tr-rule tr-rule-checked-{{$selected}}" data-id="{{$rule->id}}" data-selection='{{$selected}}' id="tr-rule-{{$rule->id}}">
                            <td>
                                <span class="condition-text">{{ucwords(strtolower($rule->cond))}}</span>
                                <hr class="hr-small-margin" />
                                <input type="text" name="rulesconditions[{{$rule->id}}]" id="rulesconditions['{{$rule->id}}']" data-id="{{$rule->id}}" class="form-control input-sm", placeholder="Location where this condition can be found in the report..."/>
                            </td>
                            <td>
                                <table class="table table-bordered table-small-margin">
                                    <tr>
                                        <td class="text-center alert-danger">
                                            <label class="radio-inline radio-inline-rule">
                                                <input type="radio" name="rules[{{$rule->id}}]" id="rules[{{$rule->id}}]" value="Y" class="checklist-rule-radio" data-id="{{$rule->id}}" {{$selected == 'Y' ? 'checked' : ''}}> Y
                                            </label>
                                        </td>
                                        <td class="text-center alert-success">
                                            <label class="radio-inline radio-inline-rule">
                                                <input type="radio" name="rules[{{$rule->id}}]" id="rules[{{$rule->id}}]" value="N" class="checklist-rule-radio" data-id="{{$rule->id}}" {{$selected == 'N' ? 'checked' : ''}}> N
                                            </label>
                                        </td>
                                        <td class="text-center">
                                            <label class="radio-inline radio-inline-rule">
                                                <input type="radio" name="rules[{{$rule->id}}]" id="rules[{{$rule->id}}]" value="" class="checklist-rule-radio" data-id="{{$rule->id}}" {{$selected == '-' || $selected == null ? 'checked' : ''}}> -
                                            </label>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>
