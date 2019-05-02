<div class="col-md-10">
    <h2>Admin Notes</h2>
    <div class="row ">
        <div class="form-group col-md-4">
            <textarea name="admin_notes" id="admin_notes" cols="60" rows="5">{{$client->admin_notes}}</textarea>
        </div>
    </div>
</div>
@push('scripts')
    <script src="{{ masset('js/management/client_settings/edit.js') }}"></script>
@endpush
