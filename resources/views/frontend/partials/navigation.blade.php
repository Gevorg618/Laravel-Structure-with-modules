{{--Navigation area starts--}}
<div class="menu-holder clearfix">
    <div class="menu-area">
        <div class="container">
            <div class="row">

                {{--Navigation starts--}}
                <div class="col-md-12">
                    <div class="mainmenu">
                        <div class="navbar navbar-nobg">

                            <div class="navbar-header">
                                <a class="navbar-brand" href="{{ url('/') }}">
                                    <img src="{{ companyLogo() }}" style="width:120px;" alt="">
                                </a>
                                <button type="button" class="navbar-toggle" data-toggle="collapse"
                                        data-target=".navbar-collapse">
                                    <span class="sr-only">Toggle navigation</span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                    <span class="icon-bar"></span>
                                </button>
                            </div>

                            <div class="navbar-collapse collapse">
                                <nav>
                                    <ul class="nav navbar-nav navbar-right">
                                        <li class="active"><a href="{{ url('/') }}">Home</a></li>
                                        @If($navigationMenu)
                                            @foreach($navigationMenu as $navigation)
                                                @if($navigation->is_drop_down)
                                                    <li class="dropdown">
                                                        <a href="{{$navigation->url}}" class="dropdown-toggle"
                                                           data-toggle="dropdown">{{ $navigation->title }} <b
                                                                    class="caret"></b></a>
                                                        @if($navigation->childes)
                                                            <ul class="dropdown-menu">
                                                                @foreach($navigation->childes as $title => $url)
                                                                    <li>
                                                                        <a href="{{$url}}">{{ $title }}</a>
                                                                    </li>
                                                                @endforeach
                                                            </ul>
                                                        @endif
                                                    </li>
                                                @else
                                                    <li>
                                                        <a href="{{$navigation->url}}">{{ $navigation->title }}</a>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endif
                                        @php $sortedPages = getSortedCustomPages(); @endphp
                                        @foreach($sortedPages as $i => $pages)
                                            @if(count($pages) > 1)
                                                <li class="dropdown">
                                                    <a href="" class="dropdown-toggle"
                                                       data-toggle="dropdown">{{strtoupper($i)}} <b
                                                                class="caret"></b></a>
                                                    <ul class="dropdown-menu">
                                                        @foreach($pages as $page)
                                                            <li>
                                                                <a href="/{{$page->route}}">{{strtoupper($page->title)}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </li>
                                            @else
                                                <li>
                                                    <a href="/{{$pages[0]->route}}">{{strtoupper($i)}}</a>
                                                </li>
                                            @endif
                                        @endforeach
                                        <li class="hidden-xs hidden"><a href="#"><i class="fa fa-search"></i></a></li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                {{--Navigation ends--}}
            </div>
        </div>
    </div>
</div>
{{--Navigation area ends--}}