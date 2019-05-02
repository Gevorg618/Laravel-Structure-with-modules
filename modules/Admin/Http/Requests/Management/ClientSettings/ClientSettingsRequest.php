<?php

namespace Modules\Admin\Http\Requests\Management\ClientSettings;

use Illuminate\Foundation\Http\FormRequest;

class ClientSettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'descrip' => 'required|string|max:255',
            'user_group_type' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:100',
            'address1' => 'nullable|string|max:55',
            'address2' => 'nullable|string|max:55',
            'city' => 'nullable|string|max:55',
            'state' => 'nullable|string|max:55',
            'zip' => 'nullable|max:255',
            'corporate_phone' => 'nullable|string|max:55',
            'twitter' => 'nullable|string|max:250',
            'linkedin' => 'nullable|string|max:250',
            'allow_amc_submit' => 'nullable|numeric|max:1',
            'allow_amc_submit_individual' => 'nullable|numeric|max:1',
            'estimated_total_monthly_volume' => 'nullable|string|max:10',
            'estimated_total_monthly_volume_one' => 'nullable|string|max:10',
            'estimated_total_monthly_volume_two' => 'nullable|string|max:10',
            'estimated_total_monthly_volume_three' => 'nullable|string|max:10',
            'estimated_total_monthly_volume_six' => 'nullable|string|max:10',
            'estimated_product_volume_conv' => 'nullable|string|max:10',
            'estimated_product_volume_fha' => 'nullable|string|max:10',
            'estimated_product_volume_forward' => 'nullable|string|max:10',
            'estimated_product_volume_reverse' => 'nullable|string|max:10',
            'estimated_product_volume_altval' => 'nullable|string|max:10',
            'estimated_start_date' => 'nullable|string|max:55',


            ];

        if ($this->method() == "POST") {
            return $rules;

        } elseif ($this->method() == "PUT") {

//            $rules['file_upload_name'] = 'required|string|max:255';

            return $rules;

        } else {
            return [];
        }


    }

    public function messages()
    {
        return [
            'descrip.required' => 'Please complete the Group Title field',
        ];
    }


    protected function getValidatorInstance()
    {
        $validator = parent::getValidatorInstance();

        if (!$validator->fails()) {
            $input = $this->all();

            if ($this->method() == "POST") {

                $this->replace($input);
            } elseif ($this->method() == "PUT") {

                if (isset($input['show_apprtype'])) {
                    ksort($input['show_apprtype']);
                    $input['show_apprtype'] = implode(',', $input['show_apprtype']);
                } else {
                    $input['show_apprtype'] = '';
                }

                if (isset($input['show_loantype'])) {
                    ksort($input['show_loantype']);
                    $input['show_loantype'] = implode(',', $input['show_loantype']);
                } else {
                    $input['show_loantype'] = '';
                }

                if (isset($input['show_loanpurpose'])) {
                    ksort($input['show_loanpurpose']);
                    $input['show_loanpurpose'] = implode(',', $input['show_loanpurpose']);
                } else {
                    $input['show_loanpurpose'] = '';
                }

                if (isset($input['show_propertytype'])) {
                    ksort($input['show_propertytype']);
                    $input['show_propertytype'] = implode(',', $input['show_propertytype']);
                } else {
                    $input['show_propertytype'] = "";
                }

                if (isset($input['lenders_used'])) {
                    $input['lenders_used'] = implode(',', $input['lenders_used']);
                } else {
                    $input['lenders_used'] = "";
                }

                if ($input['cc_exp_month'] || $input['cc_exp_year']) {
                    $input['cc_exp'] = $input['cc_exp_month'] . '-' . $input['cc_exp_year'];
                } else {
                    $input['cc_exp'] = "";

                }
                if (!$input['lenders_used']) {
                    $input['lenders_used'] = null;
                }

                if (!$input['fnc_catch_all_user_id']) {
                    $input['fnc_catch_all_user_id'] = null;
                }

                if (!$input['fnc_client_id']) {
                    $input['fnc_client_id'] = null;
                }

                if (!$input['auto_ar_emails']) {
                    $input['auto_ar_emails'] = null;
                }

                if (!$input['payment_confirmation_additional_emails']) {
                    $input['payment_confirmation_additional_emails'] = null;
                }

                if (!$input['notify_order_placed_content']) {
                    $input['notify_order_placed_content'] = null;
                }


                if (!$input['notify_order_placed_subject']) {
                    $input['notify_order_placed_subject'] = null;
                }

                if (!$input['notify_order_placed_emails']) {
                    $input['notify_order_placed_emails'] = null;
                }

                if (!$input['valutrac_catch_all_user_id']) {
                    $input['valutrac_catch_all_user_id'] = null;
                }

                if (!$input['valutrac_client_id']) {
                    $input['valutrac_client_id'] = null;
                }

                if (!$input['realview_checklist']) {
                    $input['realview_checklist'] = null;
                }

                if (!$input['mercury_excluded_loan_numbers']) {
                    $input['mercury_excluded_loan_numbers'] = null;
                }

                if (!$input['final_report_emails']) {
                    $input['final_report_emails'] = null;
                }

                if (!$input['additional_email']) {
                    $input['additional_email'] = null;
                }

                if (!$input['integration_group_assign_keyword']) {
                    $input['integration_group_assign_keyword'] = null;
                }

                if (!$input['support_email_addresses']) {
                    $input['support_email_addresses'] = null;
                }

                if (!$input['auto_select_prefered_only_miles']) {
                    $input['auto_select_prefered_only_miles'] = null;
                }

                if (!$input['mercury_catch_all_user_id']) {
                    $input['mercury_catch_all_user_id'] = null;
                }

                if (!$input['mercury_client_id']) {
                    $input['mercury_client_id'] = null;
                }

                if (!$input['sales_com_deduct_amount']) {
                    $input['sales_com_deduct_amount'] = null;
                }

                if (!$input['salesid']) {
                    $input['salesid'] = 0;
                }

                if (!$input['salesid2']) {
                    $input['salesid2'] = 0;
                }

                if (!$input['manager']) {
                    $input['manager'] = 0;
                }




                $this->replace($input);


            }
        }

        return $validator;
    }
}
