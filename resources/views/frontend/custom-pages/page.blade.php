@extends('layouts.main')

@section('content')

@if($page)

    <section class="page-title section-big" style="
            background: url('{{$page->logo_link}}') no-repeat;
            background-position: center ;
            background-size: cover;
            position: relative;
            ">
    </section>

    <div class="container">
        <div class="single-blog-content section-big">
            <div class="row">
                <div class="col-md-9 col-sm-12 col-xs-12">
                    <h2>{{$page->title}}</h2>
                    <div id="main-content">
                        {!! $page->content !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
@stop

@push('style')
    <link href="{{asset('css/style.css')}}" rel="stylesheet">
@endpush