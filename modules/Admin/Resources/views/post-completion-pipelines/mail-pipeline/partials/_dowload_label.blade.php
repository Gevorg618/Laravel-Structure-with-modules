<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Landscape</title>
</head>
<body class="gray-bg">

<div style="width:1000px;">
    @foreach($images as $img)
        @if($loop->iteration % 2 == 0)
          <div style="width:435px;float:right;margin-right:40px;">
        @else
          <div style="width:435px;float:left;">
        @endif
            <div>
                <img style="max-width: 100%;" src="{{ $img }}" alt="">
            </div>
        </div>
        @if($loop->iteration % 2 == 0)
            <br>
            <div style="clear: both;"></div>
        @endif
    @endforeach
</div>

</body>

</html>




