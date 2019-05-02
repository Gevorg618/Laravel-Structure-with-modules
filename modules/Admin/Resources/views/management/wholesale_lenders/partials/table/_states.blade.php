<a
    href="javascript:void(0);"
    class="states"
    rel="tooltip"
    data-toggle="tooltip"
    data-trigger="hover"
    data-placement="bottom"
    data-html="true"
    data-title="{{ sprintf("<div class='align-left'>%s</div>", implode('<br />', $row->states)) }}"
>
    {{is_null($row->states_total) ? 0 : $row->states_total}}
</a>
<script>
     $(function() {
         $(".states").tooltip();
     });
</script>
