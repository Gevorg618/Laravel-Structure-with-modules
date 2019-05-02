{{--News area starts--}}
<section id="news" class="news-area section-big">
    <div class="container">

        <div class="row">
            <div class="col-md-12">
                <div class="section-title text-center">
                    <h2>Latest News</h2>
                </div>
            </div>
        </div>

        <div class="row">

            @foreach($latestNews as $news) 
                <div class="col-md-4 col-sm-6">
                    <div class="single-news">
                        <div class="news-image">
                            <a href="{{url('single-post/' . $news->id)}}">
                                <img src="{{$news->image}}" alt="">
                            </a>
                        </div>
                        <div class="news-content clearfix">
                            <a href="{{url('single-post/' . $news->id)}}">
                                <h3>{{$news->title}}</h3>
                            </a>
                            <div class="home-post-meta">
                                <p class="news-meta">
                                    <span>{{$news->created_at->format('d M Y')}}</span>
                                </p>
                            </div>
                            <p>{{ $news->short_descritpion }}</p>
                            <a href="{{ route('news.view', ['slug' => $news->slug, 'row' => $news->id]) }}" class="btn btn-black">Read More</a>
                        </div>
                    </div>
                </div>    
            @endforeach
        </div>
    </div>
</section>
{{--News area ends --}}