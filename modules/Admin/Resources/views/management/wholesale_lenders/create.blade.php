@extends('admin::layouts.master')

@section('title', 'Lenders')

@component('admin::layouts.partials._breadcrumbs', [
    'crumbs' => [
        ['title' => 'Admin User', 'url' => '#'],
        ['title' => 'Lenders', 'url' => route('admin.management.lenders')],
        ['title' => 'Adding New Lender', 'url' => route('admin.management.lenders.create')]
    ]
])
@endcomponent

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <div class="panel-body panel-body-table">
                        <div class="table-responsive">
                            <div class="tabbable tabs-left">
                                <ul class="nav nav-tabs">
                                    <li class="active">
                                        <a href="#basic_info" data-toggle="tab">Basic Info</a>
                                    </li>
                                    <li>
                                        <a href="#associations" data-toggle="tab">Associations</a>
                                    </li>
                                    <li>
                                        <a href="#order_options" data-toggle="tab">Order Options</a>
                                    </li>
                                    <li>
                                        <a href="#sales" data-toggle="tab">Sales</a>
                                    </li>
                                </ul>
                                <form action="{{route('admin.management.lenders.store')}}" method="POST">
                                    {{csrf_field()}}
                                    <div class="tab-content">
                                        @if(!empty($errors->first()))
                                            <div class="col-md-12 row" style="margin-top: 15px;">
                                                <div class="row col-md-7">
                                                    <div class="alert alert-danger alert-dismissible" role="alert">
                                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                        <span>{{ $errors->first() }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="tab-pane active" id="basic_info">
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>Basic Info</h2>
                                                    <div class="form-group row">
                                                        <label for="lender" class="col-md-2 required">Lender Title</label>
                                                        <div class="col-md-4">
                                                            <input type="text" name="lender" id="lender" value="{{old('lender')}}" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="lender_dropdown_title" class="col-md-2">Lender Dropdown Title</label>
                                                        <div class="col-md-4">
                                                            <input type="text" name="lender_dropdown_title" id="lender_dropdown_title" value="{{old('lender_dropdown_title')}}" class="form-control"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="enable_auto_select" class="col-md-2">Enable AutoSelect </label>
                                                        <div class="col-md-4">
                                                            <select name="enable_auto_select" id="enable_auto_select" class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="default_watch_list" class="col-md-2">Default Watch List </label>
                                                        <div class="col-md-4">
                                                            <select name="default_watch_list" id="default_watch_list" class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                            <p class="muted col-md-12">Select if you would like to add this lender to each new client created undert the watch list option.</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>Report Delivery</h2>
                                                    <div class="form-group row">
                                                        <label for="send_final_report" class="col-md-2">Final Report Email</label>
                                                        <div class="col-md-4">
                                                            <input type="checkbox" name="send_final_report" id="send_final_report"/>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label for="final_report_emails">Lender Dropdown Title</label>
                                                            <textarea name="final_report_emails" id="final_report_emails" cols="25" rows="3" class="form-control">{{old('final_report_emails')}}</textarea>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="final_report_emails_uw">Lender Dropdown Title</label>
                                                            <textarea name="final_report_emails_uw" id="final_report_emails_uw" cols="25" rows="3" class="form-control">{{old('final_report_emails_uw')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>Lender Address</h2>
                                                    <div class="form-group row">
                                                        <div class="col-md-6">
                                                            <label for="lender_address1" class="col-md-3">Lender Address</label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="lender_address1" id="lender_address1" value="{{old('lender_address1')}}" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label for="lender_address2" class="col-md-3">Lender Address 2</label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="lender_address2" id="lender_address2" value="{{old('lender_address2')}}" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 margin_top">
                                                            <label for="lender_city" class="col-md-3">Lender City</label>
                                                            <div class="col-md-7">
                                                                <input type="text" name="lender_city" id="lender_city" value="{{old('lender_city')}}" class="form-control"/>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-6 margin_top">
                                                            <label for="lender_state" class="col-md-3">Lender State, Zip</label>
                                                            <div class="col-md-7">
                                                                <select name="lender_state" id="lender_state" class="form-control lender_state">
                                                                    <option value="">--Select--</option>
                                                                    @foreach($states as $key => $value)
                                                                        <option value="{{$key}}">{{$value}}</option>
                                                                    @endforeach
                                                                </select>
                                                                <input type="text" name="lender_zip" id="lender_zip" class="form-control lender_zip" value="{{old('lender_zip')}}"/>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>Lender Custom State Titles</h2>
                                                    <div class="form-group">
                                                        <label for="custom_titles">One per line separated by colon &lt;STATE&gt;:&lt;TITLE&gt;. For example:<br />CA: Landmark Netowrk - CA</label>
                                                        <textarea name="custom_titles" id="custom_titles" cols="25" rows="8" class="form-control custom_titles">{{old('custom_titles')}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>TILA Auth</h2>
                                                    <div class="form-group row">
                                                        <label for="tila_auth" class="col-md-2">Require TILA Auth?</label>
                                                        <div class="col-md-2">
                                                            <select name="tila_auth" id="tila_auth" class="form-control">
                                                                <option value="1">Yes</option>
                                                                <option value="0">No</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="tila_emails" class="col-md-2">TILA Auth Email Notification</label>
                                                        <div class="col-md-4">
                                                            <textarea name="tila_emails" id="tila_emails" cols="25" rows="3" class="form-control tila_emails">{{old('tila_emails')}}</textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="associations">
                                            <div class="col-md-10 associations">
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <label for="clients" class="col-md-2 required">User Groups</label>
                                                        <div class="col-md-4">
                                                            <select name="clients[]" id="clients" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                                @foreach($userGroups as $group)
                                                                    <option value="{{$group->id}}">{{$group->descrip}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="states" class="col-md-2">States</label>
                                                        <div class="col-md-4">
                                                            <select name="states[]" id="states" class="form-control multiselect bootstrap-multiselect" multiple="multiple">
                                                                @foreach($states as $key => $value)
                                                                    <option value="{{$key}}">{{$value}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="order_options">
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>Appraisal</h2>
                                                    <div class="form-group row">
                                                        <label for="is_proposed_loan_amount" class="col-md-3">Enable Proposed Loan Amount</label>
                                                        <div class="col-md-3">
                                                            <select name="is_proposed_loan_amount" id="is_proposed_loan_amount"class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-10">
                                                <div class="row">
                                                    <h2>DocuVault</h2>
                                                    <div class="form-group row">
                                                        <label for="enable_docuvault" class="col-md-3">Enable DocuVault</label>
                                                        <div class="col-md-3">
                                                            <select name="enable_docuvault" id="enable_docuvault"class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="docuvault_require_payment" class="col-md-3">Require Up-Front Payment</label>
                                                        <div class="col-md-3">
                                                            <select name="docuvault_require_payment" id="docuvault_require_payment"class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="docuvault_fee" class="col-md-3">DocuVault Order Fee</label>
                                                        <div class="col-md-2">
                                                            <input type="number" name="docuvault_fee" id="docuvault_fee" maxlength="5" class="form-control" value="{{old('docuvault_fee')}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="mail_appr_addfee" class="col-md-3">DocuVault Mail Fee</label>
                                                        <div class="col-md-2">
                                                            <input type="number" name="mail_appr_addfee" id="mail_appr_addfee" maxlength="5" class="form-control" value="{{old('mail_appr_addfee')}}">
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="finalborroweremail" class="col-md-3">Final Report to Borrower Email</label>
                                                        <div class="col-md-3">
                                                            <select name="finalborroweremail" id="finalborroweremail"class="form-control">
                                                                <option value="H">Hide</option>
                                                                <option value="R">Required</option>
                                                                <option value="O">Optional</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="finalborrowerpostal" class="col-md-3">Final Report to Borrower Postal</label>
                                                        <div class="col-md-3">
                                                            <select name="finalborrowerpostal" id="finalborrowerpostal"class="form-control">
                                                                <option value="H">Hide</option>
                                                                <option value="R">Required</option>
                                                                <option value="O">Optional</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>AVM</h2>
                                                    <div class="form-group row">
                                                        <label for="enable_avm" class="col-md-3">Enable AVM</label>
                                                        <div class="col-md-3">
                                                            <select name="enable_avm" id="enable_avm"class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="avm_require_payment" class="col-md-3">Require Up-Front Payment</label>
                                                        <div class="col-md-3">
                                                            <select name="avm_require_payment" id="avm_require_payment"class="form-control">
                                                                <option value="0">No</option>
                                                                <option value="1">Yes</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <label for="avm_fee" class="col-md-3">AVM Order Fee</label>
                                                        <div class="col-md-2">
                                                            <input type="number" name="avm_fee" id="avm_fee" maxlength="5" class="form-control" value="{{old('avm_fee')}}">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>Appraiser Options</h2>
                                                    <div class="form-group row" style="margin-bottom: 0;">
                                                        <div class="col-md-4">
                                                            <label for="min_eoins_require_each" >E&amp;O Insurance Minimums</label>
                                                            <p class="muted">Appraisers will be required to enter E&O info that meets the group minimums before accepting orders</p>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="min_eoins_require_each" id="min_eoins_require_each" maxlength="7" class="form-control" value="{{old('min_eoins_require_each')}}">
                                                        </div>
                                                        <div class="col-md-6" style="line-height: 2.5;">
                                                            Each
                                                        </div>
                                                    </div>
                                                    <div class="form-group row">
                                                        <div class="col-md-4"></div>
                                                        <div class="col-md-2">
                                                            <input type="number" name="min_eoins_require_agg" id="min_eoins_require_agg" maxlength="7" class="form-control" value="{{old('min_eoins_require_agg')}}">
                                                        </div>
                                                        <div class="col-md-6" style="line-height: 2.5;">
                                                            Aggregate
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>Lender Specific Requirements</h2>
                                                    <p class="muted">Anything typed in this box will appear on the order details page for the clients, appraisers and admins.</p>
                                                    <div class="form-group">
                                                        <textarea name="comments" id="comments" cols="60" rows="5"></textarea>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>Lender Admin Notes</h2>
                                                    <p class="muted">Anything typed in this box will appear on the order details page for admins only!</p>
                                                    <div class="form-group">
                                                        <textarea name="admin_notes" id="admin_notes" cols="60" rows="5">{{old('admin_notes')}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="tab-pane" id="sales">
                                            <div class="col-md-10 sales">
                                                <div class="row">
                                                    <h2>Sales Information</h2>
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label for="states">Account Executive</label>
                                                            <select name="salesid" id="salesid" class="form-control">
                                                                <option value="">--Select--</option>
                                                                @foreach($listData as $key => $value)
                                                                    <optgroup label="{{$key}}">
                                                                        @foreach($value as $list_key => $list_value)
                                                                            <option value="{{$list_key}}">{{$list_value}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <label for="states">SDR</label>
                                                            <select name="salesid2" id="salesid2" class="form-control">
                                                                <option value="">--Select--</option>
                                                                @foreach($listData as $key => $value)
                                                                    <optgroup label="{{$key}}">
                                                                        @foreach($value as $list_key => $list_value)
                                                                            <option value="{{$list_key}}">{{$list_value}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if($isAdmin)
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label for="salesid_com">Appraisal Account Executive Commission</label>
                                                                <div class="input-group col-md-5">
                                                                    <input type="text" name="salesid_com" id="salesid_com" class="form-control" value="{{old('salesid_com')}}">
                                                                    <span class="input-group-addon">%</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="salesid2_com">SDR Commission</label>
                                                                <div class="input-group col-md-5">
                                                                    <input type="text" name="salesid2_com" id="salesid2_com" class="form-control" value="{{old('salesid2_com')}}">
                                                                    <span class="input-group-addon">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="form-group row">
                                                            <div class="col-md-4">
                                                                <label for="salesid_alt_com">Alternative Account Executive Commission</label>
                                                                <div class="input-group col-md-5">
                                                                    <input type="text" name="salesid_alt_com" id="salesid_alt_com" class="form-control" value="{{old('salesid_alt_com')}}">
                                                                    <span class="input-group-addon">%</span>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <label for="salesid2_alt_com">Alternative SDR Commission</label>
                                                                <div class="input-group col-md-5">
                                                                    <input type="text" name="salesid2_alt_com" id="salesid2_alt_com" class="form-control" value="{{old('salesid2_alt_com')}}">
                                                                    <span class="input-group-addon">%</span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-10 row">
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="form-group row">
                                                        <div class="col-md-4">
                                                            <label for="manager">Account Executive</label>
                                                            <select name="manager" id="manager" class="form-control">
                                                                <option value="">--Select--</option>
                                                                @foreach($listData as $key => $value)
                                                                    <optgroup label="{{$key}}">
                                                                        @foreach($value as $list_key => $list_value)
                                                                            <option value="{{$list_key}}">{{$list_value}}</option>
                                                                        @endforeach
                                                                    </optgroup>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    @if($isAdmin)
                                                        <div class="form-group">
                                                            <label for="manager_com">Appraisal Manager Commission</label>
                                                            <div class="input-group col-md-2">
                                                                <input type="text" name="manager_com" id="manager_com" class="form-control" value="{{old('manager_com')}}">
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="manager_alt_com">Alternative Manager Commission</label>
                                                            <div class="input-group col-md-2">
                                                                <input type="text" name="manager_alt_com" id="manager_alt_com" class="form-control" value="{{old('manager_alt_com')}}">
                                                                <span class="input-group-addon">%</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                    <div class="col-md-10 row">
                                                        <hr />
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>Lender Signup Logo</h2>
                                                    <p>Enter the full link to the logo that will be displayed next to the message. Use the uploader to upload files. Make sure you use https:// and not http://</p>
                                                    <div class="form-group row">
                                                        <label for="signup_logo" class="col-md-2">Logo Link</label>
                                                        <div class="col-md-6">
                                                            <input type="text" id="signup_logo" name="signup_logo" class="form-control" value="{{old('signup_logo')}}"/>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <h2>Lender Signup Message</h2>
                                                    <p>Message that will be displayed to users signing up when they came from the Lenders custom signup link.</p>
                                                    <div class="form-group">
                                                        <textarea name="signup_note" id="signup_note" cols="60" rows="5">{{old('signup_note')}}</textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12 _footer">
                                        <button type="reset" class="btn">Reset</button>
                                        <button type="submit" class="btn btn-success">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@push('style')
    <link href="{{ masset('js/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}" rel="stylesheet" />
    <link href="{{ masset('css/management/wholesale_lenders/create_edit.css') }}" rel="stylesheet" />
@endpush

@push('scripts')
    <script src="{{ masset('js/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}"></script>
    <script src="{{ masset('js/management/wholesale_lenders/create.js') }}"></script>
@endpush
