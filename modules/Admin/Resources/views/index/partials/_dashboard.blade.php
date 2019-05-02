@foreach($dashboardData as $position => $items)
    <div id="{{$position}}wrapper" class="col-md-3 index-column-placement">
        @foreach($items as $groupId => $groupData)
            @if($groupData['visible'] && $groupData['perms'] && count($groupData['items']))
                <div class="row">
                    <div class="col-lg-12 column_move" id="adminbox_{{$groupId}}">
                        <div class="panel panel-{{$groupData['class'] ?? 'default'}}">
                            <div class="panel-heading">
                                <div class="panel-title">{{$groupData['title']}}</div>
                            </div>
                            <div class="panel-body">
                                <ul class="unstyled">
                                    @foreach($groupData['items'] as $item)
                                        @if(isset($item['title']) && isset($item['url']))
                                            @if($item['url'])
                                                <li><a href="{{$item['url']}}">{{$item['title']}}</a></li>
                                            @else
                                                <li>{{$item['title']}}</li>
                                            @endif
                                        @endif

                                        @if(isset($item['html']))
                                            {!! $item['html'] !!}
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
@endforeach
