<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <meta charset="UTF-8">
    <title>Labels</title>
    <style type="text/css">
        body {
            margin: 0;
        }

        .bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 328px;
            height: 250px;
        }

        h1, h2, h3, h4, h5, h6 {
            margin: 0;
        }

        * {
            font-family: Arial, Tahoma;
        }

        .labels {
            width: 700px;
            margin: 0 auto;
        }

        .labels table {
            width: 100%;
        }

        .top-table {
            border-spacing: 15px;
        }

        .labels .level-1 {
            position: relative;
            padding-top: 28px;
            width: 328px;
            height: 250px;
            vertical-align: top;
        }

        .level-1 td {
            padding-left: 12px;
        }

        .labels .level-1 table {
            position: relative;
        }

        .primary-text {
            font-size: 12px;
            text-align: right;
            line-height: 1;
            padding-bottom: 25px;
        }

        .secondary-text {
            font-size: 17px;
            line-height: 1.1;
        }
    </style>
</head>
<body>

<div class="labels">

    <table class="top-table">

            @foreach($labels as $tdCollection)
            <tr>
                @foreach($tdCollection as $label)
                <td class="level-1">
                    <div style="position: absolute; top: 0; left: 0; width: 328px; height: 250px;">
                        <div class="bg" style="background: url(/images/label-new.png) no-repeat"></div>
                    </div>

                    <table>
                        <tr>
                            <td class="primary-text">
                                <b>Landmark Network, Inc.</b><br>
                                5805 Sepulveda Blvd Suite 801<br>
                                Van Nuys, CA 91411
                            </td>
                        </tr>
                        <tr>
                            <td class="secondary-text">
                                {!! $label !!}
                            </td>
                        </tr>
                    </table>

                </td>
                @endforeach
            </tr>
            @endforeach
    </table>

</div>

</body>
</html>