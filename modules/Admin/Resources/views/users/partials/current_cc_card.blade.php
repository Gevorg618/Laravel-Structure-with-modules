@if($profile)
<div class="alert alert-success">
    {{ sprintf("%s is on file, Expires %s", $profile->credit_number, $profile->card_exp) }}
</div>
@else
<div class="alert alert-warning">
    There is no credit card on file.
</div>
@endif