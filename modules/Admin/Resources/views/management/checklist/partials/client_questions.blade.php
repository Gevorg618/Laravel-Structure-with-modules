
<div class="questions_client_div" id="questions_div_client_{!! $clientId !!}">
    @if($clientsQuestions->get($clientId))
        @foreach($clientsQuestions->get($clientId) as $question)
            @include('management.checklist.partials.question_row')
        @endforeach
	@else
    <p class="text-muted">There Are No Questions In This Category.</p>
    @endif
</div>