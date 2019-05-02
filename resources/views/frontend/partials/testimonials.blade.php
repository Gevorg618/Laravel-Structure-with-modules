{{--Testimonial area starts--}}
<section id="testimonial" class="testimonial-area section-big">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="section-title">
                    <h2>CLIENT TESTIMONIAL</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="testimonial-list">
                    @foreach( $clientTestimonials as $testimonial)
                        <div class="single-testimonial">
                            <h3>{{$testimonial->name}}</h3>
                            <p class="desg">{{$testimonial->title}}</p>
                            <p>{!! $testimonial->content !!}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</section>
{{--Testimonial area ends--}}