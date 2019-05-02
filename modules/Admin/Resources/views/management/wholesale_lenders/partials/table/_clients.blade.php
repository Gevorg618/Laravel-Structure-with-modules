<a
    href="javascript:void(0);"
    class="clients"
    rel="tooltip"
    data-toggle="tooltip"
    data-trigger="hover"
    data-placement="bottom"
    data-html="true"
    data-title="{{ sprintf("<div class='align-left'>%s</div>", implode('<br />', $row->clients)) }}"
>
    {{$row->clients_total}}
</a>
<script>
     $(function() {
         $(".clients").tooltip();
     });
</script>
