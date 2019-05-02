@if($lenders)
    @foreach($lenders as $lenderId => $lenderDesc)
        @include('management.checklist.partials.lender_row')
    @endforeach
@endif