@extends('layouts.main')

@section('content')

@include('frontend.partials.slider')

@include('frontend.partials.stats')

@include('frontend.partials.services')

@include('frontend.partials.team')

@include('frontend.partials.testimonials')

@include('frontend.partials.news')

@include('frontend.partials.contactus')

@include('frontend.partials.footer')

<script>
    GlobalScope = {};
    window.GlobalScope = GlobalScope;

    GlobalScope.header_carousel = @php echo isset($carouselItems) ? json_encode($carouselItems) : '' @endphp;
    GlobalScope.contact_us = @php echo isset($contactUs) ? json_encode($contactUs) : '' @endphp;
</script>

@stop