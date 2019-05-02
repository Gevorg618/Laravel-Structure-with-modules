<div class="btn-group">
  <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a class="actions_request" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-edit',  $pricingVersion->id) }}" data-type="update_pricing"> Update</a></li>
    <li><a href="{{ route('admin.autoselect.pricing.versions.pricing-clients-download', $pricingVersion->id) }}"> Clients</a></li>
    <li><a class="actions_request" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-add-client', $pricingVersion->id) }}" data-type="add_client"> Add Client</a></li>
    <li><a href="{{ route('admin.autoselect.pricing.versions.pricing-download', $pricingVersion->id) }}"> Download</a></li>
    <li><a class="actions_request" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-view-by-state', $pricingVersion->id) }}" data-type="view_pricing"> View Pricing</a></li>
    <li><a class="actions_request" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-edit-addendas', $pricingVersion->id) }}" data-type="view_addenda"> View Addendas</a></li>
  </ul>
</div>