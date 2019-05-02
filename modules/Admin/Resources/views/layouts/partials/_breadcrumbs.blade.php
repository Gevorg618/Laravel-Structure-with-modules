@section('breadcrumbs')
    @if(isset($crumbs) || isset($actions))
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-6 col-md-6 col-sm-8 col-xs-12">
            <h2>
                @if(View::hasSection('back_button'))
                    @yield('back_button')
                @endif
                @yield('title')
            </h2>
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">Admin Home</a></li>
                @if(isset($crumbs) && $crumbs)
                  @foreach($crumbs as $i => $item)
                    @if(isset($item['url']) && isset($item['title']))
                      <li><a href='{{ $item['url'] }}'>{{ $item['title'] }}</a></li>
                    @endif
                  @endforeach
                @endif
            </ol>
        </div>
        @if(isset($actions) && $actions)
        <div class="col-lg-6 col-md-6 col-sm-4 col-xs-12">
          <div class="title-action">
            @foreach($actions as $i => $item)
              @if(isset($item['url']) && isset($item['title']))
                  <a href='{{ $item['url'] }}' class='btn btn-success {{isset($item["class"]) ? $item["class"] : ""}}'>{!! $item['title'] !!}</a>
              @endif
            @endforeach
          </div>
        </div>
        @endif
    </div>
    @endif
@endsection
