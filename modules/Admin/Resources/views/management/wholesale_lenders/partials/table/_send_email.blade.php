<a
    href="javascript:void(0);"
    class="_email"
    rel="tooltip"
    data-toggle="tooltip"
    data-trigger="hover"
    data-placement="bottom"
    data-html="true"
    data-title="{{ sprintf("<div class='align-left'>Emails: %s</div>", implode('<br />', explode("\n", $row->final_report_emails))) }}"
>
    {{$row->send_final_report ? 'On' : 'Off'}}
</a>
<script>
     $(function() {
         $("._email").tooltip();
     });
</script>
