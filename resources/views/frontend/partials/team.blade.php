{{--Team area starts--}}
<section id="team" class="team-area section-big">
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <div class="section-title">
                    <h2>MEET OUR TEAM</h2>
                </div>
            </div>
        </div>
        <div class="row">
            @foreach($teamMembers as $member)
                <div class="col-md-3 col-sm-6">
                    <div class="team-member">
                        <div class="member-image">
                            <img src="{{$member->image}}" alt="">
                            <div class="member-social">
                                <div class="put-center">
                                    @if($member->social_links)
                                        @foreach($member->social_links as $icon => $url)
                                            <a href="{{$url}}"><i class="{{$icon}}"></i></a>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="member-info">
                            <h3>{{$member->name}}</h3>
                            <p class="text-muted">{{$member->title}}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
{{--Team area ends--}}