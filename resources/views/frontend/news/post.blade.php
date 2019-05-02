@extends('layouts.main')

@section('title', $row->title)

@section('content')
    <div class="container">
        <div class="single-blog-content section-big">
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <div class="single-post">
                        <h1>{{ $row->title }}</h1>
                        <div class="featured-img">
                            <img src="{{$row->image}}" alt="">
                            <p class="news-meta text-muted">
                                <span><i class="fa fa-calendar"></i> {{$row->created_at->format('d M Y')}} </span>
                            </p>
                        </div>
                        <h2>{{$row->short_description}}</h2>
                        {!! $row->content !!}
                    </div>
                </div>
                <!-- Sidebar Starts -->
                <div class="col-md-3 col-sm-12 col-xs-12 hidden">

                    <div class="widget widget-search">
                        <form name="search" method="get" action="#">
                            <fieldset>
                                <input id="s" type="search" name="search" placeholder="" required="">
                                <label for="s">Search</label>
                                <input type="submit" name="submit" value="">
                            </fieldset>
                        </form>
                    </div>
                    @if(!empty($latestNews))
                    <div class="widget widget-recent-posts">
                        <h3 class="widget-title">Recent Posts</h3>
                        <ul>
                            @foreach($latestNews as $news)
                                <li>
                                    <img src="{{$news->image}}" alt="">
                                    <a href="{{url('single-post/' . $news->id)}}" class="post-title"> {{$news->title}} </a>
                                    <div class="post-details">
                                        <span><i class="fa fa-calendar"></i> {{$news->created_at->format('d M')}} </span>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@stop