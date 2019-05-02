{{--Slider area starts--}}
<section id="slider">
    <div id="carousel-example-generic" class="carousel slide carousel-fade">
        <div class="carousel-inner" role="listbox">
            @if( $carouselItems )
                @foreach( $carouselItems as $carouselItem )
                    <div class="item {{$loop->first ? 'active' : ''}}"
                         style="background-image: url('{{$carouselItem->desktop_image}}') no-repeat; background-size: cover;" data-id="{{$carouselItem->id}}">
                        <div class="table">
                            <div class="table-cell">
                                <div class="intro-text">
                                    <h2>{{$carouselItem->title}}</h2>
                                    <p>{{$carouselItem->description}}</p>

                                    @foreach($carouselItem->buttons as $title => $url)
                                        <a href="{{$url}}"
                                           class="btn {{$loop->first ? '' : 'btn-trnsp'}}">{{$title}}</a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        {{--End Wrapper for slides--}}


        {{--Carousel Pagination--}}
        <ol class="carousel-indicators hidden">
            <li data-target="header-carousel" data-slide-to="0" class="active"></li>
            <li data-target="header-carousel" data-slide-to="1"></li>
            <li data-target="header-carousel" data-slide-to="2"></li>
        </ol>

        {{--Slider left right button--}}
        @if( $carouselItems && count($carouselItems) >= 2 )
            <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                <img src="{{asset('build/frontend/images/left-arrow.png')}}" alt="">
            </a>
            <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
                <img src="{{asset('build/frontend/images/right-arrow.png')}}" alt="">
            </a>
        @endif
    </div>
</section>
{{--Slider area ends--}}