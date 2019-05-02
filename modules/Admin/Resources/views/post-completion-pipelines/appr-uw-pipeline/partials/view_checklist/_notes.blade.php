<div class="row">
    <div class="col-md-12">
        @if($notes && count($notes))
            <div class="alert alert-warning">
                <ol>
                @foreach($notes as $note)
                    <li>{!!$note!!}</li>
                @endforeach
                </ol>
            </div>
        @endif
    </div>
    <div class="col-md-12"><hr /></div>
</div>
