<div class="questions_lender_div" id="questions_div_lender_{!! $lenderId !!}">
    @if($lendersQuestions->get($lenderId))
        @foreach($lendersQuestions->get($lenderId) as $question)
            @include('management.checklist.partials.question_row')
        @endforeach
	@else
    <p class="text-muted">There Are No Questions In This Category.</p>
    @endif
</div>
