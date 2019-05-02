{{--Service area starts--}}
<section id="service" class="service-area section-big">
    <div class="container">

        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section-title">
                    <h2>SERVICE WE PROVIDE</h2>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="home-services">

                @foreach($servicesWeProvide as $service)
                    <div class="service-box ">
                        <i class="{{$service->icon}}"></i>
                        {{--<img src="{{$service->logo}}" alt="">--}}
                        <h3>{{ $service->title }}</h3>
                        <p>{{ $service->description }}</p>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
{{--Service area ends--}}