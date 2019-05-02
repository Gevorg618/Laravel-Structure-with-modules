<div class="btn-group">
    <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
    <ul class="dropdown-menu pull-right" role="menu">
        <li>
            @if(!$row->is_escalated_worked_today)
                <a href="#" class="link-style mark-as-worked" data-id="{{$row->id}}">Mark As Worked Today</a>
            @else
                <a href="#" class="link-style mark-as-worked" data-id="{{$row->id}}">Un-Mark As Worked Today</a>
            @endif
        </li>
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
        color: #676a6c!important;
    }
    .link-style:hover {
        color: #262626;
        background-color: #f5f5f5;
    }
</style>
