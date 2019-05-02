<div class="dropdown">
    <button class="btn btn-primary btn-xs dropdown-toggle" type="button" data-toggle="dropdown">
        Options
        <span class="caret"></span>
    </button>
    <ul class="dropdown-menu options_menu">
        @if($row->document_id)
            <li>
                <a href="#" target="_blank">View Document</a>
            </li>
        @endif

        @if($row->is_purchase_worked_today)
            <li>
                <a href='javascript:;' class='mark-as-worked' data-id="{{$row->id}}">Un-Mark As Worked Today</a>
            </li>
        @else
            <li>
                <a href='javascript:;' class='mark-as-worked' data-id="{{$row->id}}">Mark As Worked Today</a>
            </li>
        @endif

        @if($row->is_contract_reviewed)
            <li>
                <a href='javascript:;' class='mark-as-unreviewed' data-id="{{$row->id}}">Un-Mark As Reviewed</a>
            </li>
        @else
            <li>
                <a href='javascript:;' class='mark-as-reviewed' data-id="{{$row->id}}">Mark As Reviewed</a>
            </li>
        @endif

        @if(!$row->is_contract_reviewed)
            <li>
                <a href='javascript:;' class='mark-as-requested' data-id="{{$row->id}}">Documents Requested</a>
            </li>
        @endif
    </ul>
</div>
