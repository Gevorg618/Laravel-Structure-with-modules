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
                      <td align="center" valign="middle">
                        <table width="100%" border="0" align="center" cellpadding="0" cellspacing="0">
                          <tbody>
                            <tr>
                              <td align="center" valign="middle" style="font-family:'Open Sans', Verdana, Arial; font-size:36px; color:#2273cc; font-weight:normal;" mc:edit="nm3-02">
                                <multiline>{{ $title }}</multiline>
                              </td>
                            </tr>
                            <tr>
                              <td height="25" align="center" valign="middle" style="line-height:25px; font-size:25px;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td valign="middle" style="font-family:'Open Sans', Verdana, Arial; font-size:13px; color:#131313; font-weight:normal; line-height:32px;" mc:edit="nm3-03">
                                <multiline>Hi {{ $user->fullname }},</multiline>
                              </td>
                            </tr>
                            <tr>
                              <td valign="middle" style="font-family:'Open Sans', Verdana, Arial; font-size:13px; color:#131313; font-weight:normal; line-height:32px;" mc:edit="nm3-03">
                                <multiline>We got a request to reset your password. To start the process, please click the following button:</multiline>
                              </td>
                            </tr>
                            <tr>
                              <td height="25" align="center" valign="middle" style="line-height:25px; font-size:25px;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center" valign="middle">
                                <table width="175" border="0" align="center" cellpadding="0" cellspacing="0">
                                  <tbody>
                                    <tr>
                                      <td height="48" align="center" valign="middle" style="background:#2273cc; font-family:'Open Sans', Verdana, Arial; font-size:15px; color:#FFF; font-weight:normal; text-transform:uppercase; line-height:28px; -moz-border-radius: 25px; border-radius: 25px;" mc:edit="nm3-06">
                                        <multiline>
                                          <a href="{{ route('dashboard.reset', ['token' => $token, 'email' => $user->email]) }}" style="text-decoration:none; color:#FFF;">Reset Password</a>
                                        </multiline>
                                      </td>
                                    </tr>
                                  </tbody>
                                </table>
                              </td>
                            </tr>
                            <tr>
                              <td height="25" align="center" valign="middle" style="line-height:25px; font-size:25px;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td valign="middle" style="font-family:'Open Sans', Verdana, Arial; font-size:13px; color:#131313; font-weight:normal; line-height:32px;" mc:edit="nm3-03">
                                <multiline>If the above link doesn’t work, copy and paste the following URL in a new browser window. The URL will expire in 24 hours for security reasons. If you didn’t make this request, simply ignore this message.</multiline>
                              </td>
                            </tr>
                            <tr>
                              <td height="25" align="center" valign="middle" style="line-height:25px; font-size:25px;">&nbsp;</td>
                            </tr>
                            <tr>
                              <td valign="middle" style="font-family:'Open Sans', Verdana, Arial; font-size:13px; color:#131313; font-weight:normal; line-height:20px;" mc:edit="nm3-03">
                                <a href="{{ route('dashboard.reset', ['token' => $token, 'email' => $user->email]) }}" style="text-decoration:none; color:#2273cc;word-break:break-all;">{{ route('dashboard.reset', ['token' => $token]) }}</a>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </td>
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
