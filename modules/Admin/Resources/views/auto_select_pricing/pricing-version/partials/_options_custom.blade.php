<div class="btn-group">
  <a href="#" data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">Actions <span class="caret"></span></a>
  <ul class="dropdown-menu pull-right" role="menu">
    <li><a class="custom_request_option" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-custom-edit',  $apprStatePrice->groupData->id) }}" data-type="update__custom_pricing"> Update</a></li>
    <li><a href="{{ route('admin.autoselect.pricing.versions.pricing-client-download', $apprStatePrice->groupData->id) }}"> Download</a></li>
    <li><a class="custom_request_option" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-view-by-client', $apprStatePrice->groupData->id) }}" data-type="view_pricing"> View Pricing</a></li>
    <li><a class="custom_request_option" data-attr="{{ route('admin.autoselect.pricing.versions.pricing-client-edit-addendas', $apprStatePrice->groupData->id) }}" data-type="view_addenda"> View Addendas</a></li>
    <li><a href="{{ route('admin.autoselect.pricing.versions.pricing-client-delete', $apprStatePrice->groupData->id) }}"> Delete</a></li>
  </ul>
</div>