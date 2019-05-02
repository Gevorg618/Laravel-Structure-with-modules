<div class="questions_div" id="questions_div_category_{!! $row->id !!}">
@if(isset($categoriesQuestions[$row->id]))
    @forelse($categoriesQuestions[$row->id] as $question)
        @include('management.checklist.partials.question_row')
    @empty
        <p class="text-muted">There Are No Questions In This Category.</p>
    @endforelse
@else
    <p class="text-muted">There Are No Questions In This Category.</p>
@endif
</div>
