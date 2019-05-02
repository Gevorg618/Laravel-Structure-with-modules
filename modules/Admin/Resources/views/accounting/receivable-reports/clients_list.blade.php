<table class="active-users table table-striped table-bordered table-hover">
    <tr>
        <th style='width:20px;padding:2px;'><input type="checkbox" name="select-all-invoiced" value="1" class="select-all-checkboxes" id="check-all"></th>
        <th>Client</th>
        <th>Credits</th>
        <th>&lt; 60</th>
        <th>60-90</th>
        <th>90-120</th>
        <th>120+</th>
        <th>Past Due</th>
        <th>Total</th>
        <th style='display:none;'>AVG Margin</th>
    </tr>
    @foreach($rows as $row)
    <tr>
        <td style='width:20px;padding:2px;'>
            <input type="checkbox" name="clients[]" value="{{ $row['data']->id }}" class="client-checkbox">
        </td>
        <td style="text-align:left;">
            @if($row['data']->net_days != 'DNB')
            <small>(NET {!! $row['data']->net_days !!})</small>
            @endif
            <a href="{{ sprintf('/admin/usergroups.php?gid=%s&ap=1', $row['data']->id) }}" target="_blank">{{ $row['data']->descrip }}</a>
        <td class="c-credits align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, 'credits') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['credits']['count'])) }}" target="_blank">${{ number_format($row['counts']['credits']['due'], 2) }}</a>
        </td>
        <td class="c-60 align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, '60') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['60']['count'])) }}" target="_blank">${{ number_format($row['counts']['60']['due'], 2) }}</a>
        </td>

        <td class="c-60-90 align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, '60-90') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['60-90']['count'])) }}" target="_blank">${{ number_format($row['counts']['60-90']['due'], 2) }}</a>
        </td>
        <td class="c-90-120 align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, '90-120') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['90-120']['count'])) }}" target="_blank">${{number_format($row['counts']['90-120']['due'], 2)}}</a>
        </td>
        <td class="c-120 align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, '120') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['120']['count'])) }}" target="_blank">${{ number_format($row['counts']['120']['due'], 2) }}</a>
        </td>
        <td class="c-pastdue align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, 'past') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['past']['count'])) }}" target="_blank">${{ number_format($row['counts']['past']['due'], 2) }}</a>
        </td>
        <td class="c-total align-left">
            <a href="{{ sprintf('/admin/accounting/receivable-reports.php?action=view-client&client=%s&type=%s', $row['data']->id, 'total') }}" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($row['counts']['total']['count'])) }}" target="_blank">${{ number_format($row['counts']['total']['due'], 2) }}</a>
        </td>
        <td class="c-avg align-left" style='display:none;'>
            ${{ number_format($row['counts']['avgmargin']['margin'], 2) }}
        </td>
    </tr>
    @endforeach
    <tr>
        <th>&nbsp;</th>
        <th class='align-left'>{{ $title }}</th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['credits']['count'])) }}">${{ number_format($counts['credits']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['60']['count'])) }}">${{ number_format($counts['60']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['60-90']['count'])) }}">${{ number_format($counts['60-90']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['90-120']['count'])) }}">${{ number_format($counts['90-120']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['120']['count'])) }}">${{ number_format($counts['120']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['past']['count'])) }}">${{ number_format($counts['past']['due'], 2) }}</a>
        </th>
        <th class='align-left'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['total']['count'])) }}">${{ number_format($counts['total']['due'], 2) }}</a>
        </th>
        <th class='align-left' style='display:none;'>
            <a href="javascript:void(0)" rel="tooltip" title="{{ sprintf('Total Orders: %s', number_format($counts['avgmargin']['count'])) }}">${{ number_format($counts['avgmargin']['margin'], 2) }}</a>
        </th>
    </tr>
</table>
{{ $paginator->links() }}
{!! Form::open([
    'route' => ['admin.accounting.receivable-reports.view-clients'],
    'id' => 'view_clients_form',
    'class' => 'form-horizontal',
    'method' => 'GET'
]) !!}
<div class="submit">
    {!! Form::hidden('ids', null, ['id' => 'ids']) !!}
    {!! Form::hidden('filter', null, ['id' => 'filter_hidden']) !!}
    {!! Form::hidden('credits', null, ['id' => 'credits_hidden']) !!}
    {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
</div>
{!! Form::close() !!}