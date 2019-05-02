{{--fun-facts area starts--}}
<section id="fun-facts" class="fun-facts-area section-big">
    <div class="container">
        <div class="row">
            @foreach($stats as $stat)
                <div class="col-xs-12 col-sm-6 col-md-3">
                    <div class="fun-fact-box fone clearfix">
                        <div class="fun-fact">
                            <i class="{{$stat->icon}}"></i>
                            <p>{{$stat->title}}</p>
                            <h3><span class="timer">{{$stat->stat_number}}</span></h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
{{--fun-facts area ends--}}