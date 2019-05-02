@extends('emails.layouts.main')

@section('content')
<!--Text Part Start-->
<table width="100%" border="0" align="center" cellpadding="0" cellspacing="0" mc:repeatable="Note Mail" mc:variant="nm10-4-Text Part">
  <tr>
      <td align="center" valign="top">
        <table width="600" border="0" align="center" cellpadding="0" cellspacing="0" class="main">
            <tr>
              <td align="center" valign="middle" bgcolor="#FFFFFF" style="-moz-border-radius: 0px 0px 8px 8px; border-radius: 0px 0px 8px 8px;">
                  <table width="490" border="0" cellspacing="0" cellpadding="0" class="two-left-inner">
                    <tr>
                        <td height="60" align="center" valign="middle" style="line-height:60px; font-size:60px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-family:'Open Sans', Verdana, Arial; font-size:16px; color:#121212; font-weight:normal;" mc:edit="nm20-06">
                          <multiline>{{ $subject }}</multiline>
                        </td>
                    </tr>
                    <tr>
                        <td height="20" align="center" valign="middle" style="line-height:20px; font-size:20px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td align="left" valign="top" style="font-family:'Open Sans', Verdana, Arial; font-size:14px; color:#313131; font-weight:normal; line-height:28px;" mc:edit="nm20-05">
                          <multiline>
                            {!! $content !!}
                          </multiline>
                        </td>
                    </tr>
                    <tr>
                        <td height="30" align="center" valign="middle" style="line-height:30px; font-size:30px;">&nbsp;</td>
                    </tr>
                    <tr>
                        <td height="50" align="center" valign="middle" style="line-height:50px; font-size:50px;">&nbsp;</td>
                    </tr>
                  </table>
              </td>
            </tr>
        </table>
      </td>
  </tr>
</table>
<!--Text Part End-->
@endsection
