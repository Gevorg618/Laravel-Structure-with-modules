@foreach($generalChecklist as $sectionName => $items)
    <div class="row checklist-section-row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-md-11 pull-left">
                            <h3 class="panel-title">{{$sectionName}} (<span class="count-section">{{count($items)}}</span>)</h3>
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
                        @foreach($items as $item)
                            @php
                                $current = '-';
                                if($item->answer) {
                                    $current = $item->answer;
                                }
                                $selected = $current;
                            @endphp
                            <tr class="tr-rule general-condition tr-rule-checked-{{$selected}}"
                                data-id="{{'g-' . $item->id}}" data-selection='{{$selected}}'
                                id="tr-rule-{{$item->id}}">
                                <td>{{convertOrderKeysToValues($item->title, $order)}}</td>

                                <input type='hidden' name='general-checklist-correction-{{$item->id}}' value='{{json_encode(convertOrderKeysToValues($item->correction, $order))}}' id='general-checklist-correction-{{$item->id}}' />
                                <td>
                                    <table class="table table-bordered table-small-margin">
                                        <tr>
                                            <td class="text-center alert-danger">
                                                <label class="radio-inline radio-inline-rule">
                                                    <input type="radio" name="general['{{$item->id}}']" value="Y"
                                                           data-id="{{$item->id}}"
                                                           class="checklist-rule-radio" {{$selected == 'Y' ? 'checked' : ''}}>
                                                    Y
                                                </label>
                                            </td>
                                            <td class="text-center alert-success">
                                                <label class="radio-inline radio-inline-rule">
                                                    <input type="radio" name="general['{{$item->id}}']" value="N"
                                                           data-id="{{$item->id}}"
                                                           class="checklist-rule-radio" {{$selected == 'N' ? 'checked' : ''}}>
                                                    N
                                                </label>
                                            </td>
                                            <td class="text-center">
                                                <label class="radio-inline radio-inline-rule">
                                                    <input type="radio" name="general['{{$item->id}}']" value="-"
                                                           data-id="{{$item->id}}"
                                                           class="checklist-rule-radio" {{$selected == '-' || $selected == null ? 'checked' : ''}}>
                                                    -
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
@endforeach
