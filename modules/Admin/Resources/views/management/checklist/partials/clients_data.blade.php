@if($clients)
    @foreach($clients as $clientId => $clientDesc)
        @include('management.checklist.partials.client_row')
    @endforeach
@endif