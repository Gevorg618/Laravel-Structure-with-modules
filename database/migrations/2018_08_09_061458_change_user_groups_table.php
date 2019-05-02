<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Models\Clients\Client;

class ChangeUserGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        $clients = Client::select('id', 'show_price', 'show_paylater', 'show_factor',
            'show_paynow', 'show_checkpaynow', 'show_uwallusers', 'show_uwmgrusers', 'show_reqfha',
            'hide_borrestimate', 'hide_units', 'hide_commentswhenorder', 'show_prevorders', 'alert_zeroest', 'show_cancelbeforeassign',
            'show_holdbeforefha', 'exclude_thankyoucards', 'show_apprnameinv', 'show_multiupload', 'show_move_order', 'show_move_subgroup',
            'show_appr_radius', 'copy_supporttix', 'req_loannum', 'opt_contactentry', 'req_fha_appr', 'auto_req_investdocs', 'show_appr_acceptedemail',
            'attach_hvcc_cert', 'attach_hvcc_cert_forfha', 'attach_fha_cert', 'restrict_lenderinfo_groupinfo', 'borrower_payment_emails',
            'borrower_payment_calls', 'customlogin', 'customlogin_branded', 'order_directtohold', 'req_cert_appr', 'hidelogsfromuw', 'hidelegaldescrip',
            'show_uw_compmgr', 'supress_inspcomplete', 'under_master_group', 'create_ticket_assignment', 'skip_algorithm', 'disable_assign', 'hvcc_signature_page',
            'allow_umbillmelater', 'item_fhainvoice', 'item_allinvoice', 'truthinlending', 'show_genericwholesale', 'require_borrower_auth', 'new_require_borrower_auth',
            'new_require_borrower_auth_delay', 'require_purchasecontract', 'autopop_reportdue', 'requires_aiready', 'review_reminder', 'create_ticket_outside_range',
            'regen_icc_cert_postalmail', 'sub_deliveryfee_frominvoice', 'allow_pdfdocument_assembly', 'attach_borrcoverletter', 'show_user_invoice', 'auditpro_reports',
            'show_borrow_estimates_client_only', 'followup_order', 'hold_order_delay_cc_payment', 'show_previous_bpovalue', 'allow_cod_payment', 'allow_partial_payment',
            'send_xml_report', 'show_finalconvert', 'emailcontrol_status', 'emailcontrol_support', 'emailcontrol_final', 'active', 'wholesale_options', 'hide_al_lenderclient',
            'add_ihouse_proptypes')->get()->toArray();


        Schema::table('user_groups', function (Blueprint $table) {
            $table->dropColumn('show_price');
            $table->dropColumn('show_paylater');
            $table->dropColumn('show_paynow');
            $table->dropColumn('show_checkpaynow');
            $table->dropColumn('show_uwallusers');
            $table->dropColumn('show_uwmgrusers');
            $table->dropColumn('show_reqfha');
            $table->dropColumn('show_factor');
            $table->dropColumn('hide_borrestimate');
            $table->dropColumn('hide_units');
            $table->dropColumn('hide_commentswhenorder');
            $table->dropColumn('show_prevorders');
            $table->dropColumn('alert_zeroest');
            $table->dropColumn('show_cancelbeforeassign');
            $table->dropColumn('show_holdbeforefha');
            $table->dropColumn('exclude_thankyoucards');
            $table->dropColumn('show_apprnameinv');
            $table->dropColumn('show_multiupload');
            $table->dropColumn('show_move_order');
            $table->dropColumn('show_move_subgroup');
            $table->dropColumn('show_appr_radius');
            $table->dropColumn('copy_supporttix');
            $table->dropColumn('req_loannum');
            $table->dropColumn('opt_contactentry');
            $table->dropColumn('req_fha_appr');
            $table->dropColumn('auto_req_investdocs');
            $table->dropColumn('show_appr_acceptedemail');
            $table->dropColumn('attach_hvcc_cert');
            $table->dropColumn('attach_hvcc_cert_forfha');
            $table->dropColumn('attach_fha_cert');
            $table->dropColumn('restrict_lenderinfo_groupinfo');
            $table->dropColumn('borrower_payment_emails');
            $table->dropColumn('borrower_payment_calls');
            $table->dropColumn('customlogin');
            $table->dropColumn('customlogin_branded');
            $table->dropColumn('order_directtohold');
            $table->dropColumn('req_cert_appr');
            $table->dropColumn('hidelogsfromuw');
            $table->dropColumn('hidelegaldescrip');
            $table->dropColumn('show_uw_compmgr');
            $table->dropColumn('supress_inspcomplete');
            $table->dropColumn('under_master_group');
            $table->dropColumn('create_ticket_assignment');
            $table->dropColumn('skip_algorithm');
            $table->dropColumn('disable_assign');
            $table->dropColumn('hvcc_signature_page');
            $table->dropColumn('allow_umbillmelater');
            $table->dropColumn('item_fhainvoice');
            $table->dropColumn('item_allinvoice');
            $table->dropColumn('truthinlending');
            $table->dropColumn('show_genericwholesale');
            $table->dropColumn('require_borrower_auth');
            $table->dropColumn('new_require_borrower_auth');
            $table->dropColumn('new_require_borrower_auth_delay');
            $table->dropColumn('require_purchasecontract');
            $table->dropColumn('autopop_reportdue');
            $table->dropColumn('requires_aiready');
            $table->dropColumn('review_reminder');
            $table->dropColumn('create_ticket_outside_range');
            $table->dropColumn('regen_icc_cert_postalmail');
            $table->dropColumn('sub_deliveryfee_frominvoice');
            $table->dropColumn('allow_pdfdocument_assembly');
            $table->dropColumn('attach_borrcoverletter');
            $table->dropColumn('show_user_invoice');
            $table->dropColumn('auditpro_reports');
            $table->dropColumn('show_borrow_estimates_client_only');
            $table->dropColumn('followup_order');
            $table->dropColumn('hold_order_delay_cc_payment');
            $table->dropColumn('show_previous_bpovalue');
            $table->dropColumn('allow_cod_payment');
            $table->dropColumn('allow_partial_payment');
            $table->dropColumn('send_xml_report');
            $table->dropColumn('show_finalconvert');
            $table->dropColumn('emailcontrol_status');
            $table->dropColumn('emailcontrol_support');
            $table->dropColumn('emailcontrol_final');
            $table->dropColumn('active');
            $table->dropColumn('wholesale_options');
            $table->dropColumn('hide_al_lenderclient');
            $table->dropColumn('add_ihouse_proptypes');

        });

        Schema::table('user_groups', function (Blueprint $table) {
            $table->tinyInteger('show_price')->default(0);
            $table->tinyInteger('show_paylater')->default(1);
            $table->tinyInteger('show_paynow')->default(1);
            $table->tinyInteger('show_checkpaynow')->default(0);
            $table->tinyInteger('show_uwallusers')->default(0);
            $table->tinyInteger('show_uwmgrusers')->default(0);
            $table->tinyInteger('show_reqfha')->default(0);
            $table->tinyInteger('show_factor')->default(0);
            $table->tinyInteger('hide_borrestimate')->default(0);
            $table->tinyInteger('hide_units')->default(0);
            $table->tinyInteger('hide_commentswhenorder')->default(0);
            $table->tinyInteger('show_prevorders')->default(0);
            $table->tinyInteger('alert_zeroest')->default(0);
            $table->tinyInteger('show_cancelbeforeassign')->default(0);
            $table->tinyInteger('show_holdbeforefha')->default(0);
            $table->tinyInteger('exclude_thankyoucards')->default(0);
            $table->tinyInteger('show_apprnameinv')->default(0);
            $table->tinyInteger('show_multiupload')->default(0);
            $table->tinyInteger('show_move_order')->default(0);
            $table->tinyInteger('show_move_subgroup')->default(0);
            $table->tinyInteger('show_appr_radius')->default(0);
            $table->tinyInteger('copy_supporttix')->default(0);
            $table->tinyInteger('req_loannum')->default(0);
            $table->tinyInteger('opt_contactentry')->default(0);
            $table->tinyInteger('req_fha_appr')->default(0);
            $table->tinyInteger('auto_req_investdocs')->default(0);
            $table->tinyInteger('show_appr_acceptedemail')->default(0);
            $table->tinyInteger('attach_hvcc_cert')->default(1);
            $table->tinyInteger('attach_hvcc_cert_forfha')->default(1);
            $table->tinyInteger('attach_fha_cert')->default(0);
            $table->tinyInteger('restrict_lenderinfo_groupinfo')->default(0);
            $table->tinyInteger('borrower_payment_emails')->default(1);
            $table->tinyInteger('borrower_payment_calls')->default(1);
            $table->tinyInteger('customlogin')->default(0);
            $table->tinyInteger('customlogin_branded')->default(1);
            $table->tinyInteger('order_directtohold')->default(0);
            $table->tinyInteger('req_cert_appr')->default(0);
            $table->tinyInteger('hidelogsfromuw')->default(0);
            $table->tinyInteger('hidelegaldescrip')->default(1);
            $table->tinyInteger('show_uw_compmgr')->default(1);
            $table->tinyInteger('supress_inspcomplete')->default(0);
            $table->tinyInteger('under_master_group')->default(0);
            $table->tinyInteger('create_ticket_assignment')->default(0);
            $table->tinyInteger('skip_algorithm')->default(0);
            $table->tinyInteger('disable_assign')->default(0);
            $table->tinyInteger('hvcc_signature_page')->default(0);
            $table->tinyInteger('allow_umbillmelater')->default(0);
            $table->tinyInteger('item_fhainvoice')->default(0);
            $table->tinyInteger('item_allinvoice')->default(0);
            $table->tinyInteger('truthinlending')->default(0);
            $table->tinyInteger('show_genericwholesale')->default(0);
            $table->tinyInteger('require_borrower_auth')->default(0);
            $table->tinyInteger('new_require_borrower_auth')->default(0);
            $table->tinyInteger('new_require_borrower_auth_delay')->default(0);
            $table->tinyInteger('require_purchasecontract')->default(0);
            $table->tinyInteger('autopop_reportdue')->default(0);
            $table->tinyInteger('requires_aiready')->default(0);
            $table->tinyInteger('review_reminder')->default(0);
            $table->tinyInteger('create_ticket_outside_range')->default(0);
            $table->tinyInteger('regen_icc_cert_postalmail')->default(0);
            $table->tinyInteger('sub_deliveryfee_frominvoice')->default(0);
            $table->tinyInteger('allow_pdfdocument_assembly')->default(0);
            $table->tinyInteger('attach_borrcoverletter')->default(0);
            $table->tinyInteger('show_user_invoice')->default(0);
            $table->tinyInteger('auditpro_reports')->default(0);
            $table->tinyInteger('show_borrow_estimates_client_only')->default(0);
            $table->tinyInteger('followup_order')->default(0);
            $table->tinyInteger('hold_order_delay_cc_payment')->default(0);
            $table->tinyInteger('show_previous_bpovalue')->default(0);
            $table->tinyInteger('allow_cod_payment')->default(0);
            $table->tinyInteger('allow_partial_payment')->default(0);
            $table->tinyInteger('send_xml_report')->default(1);
            $table->tinyInteger('show_finalconvert')->default(1);
            $table->tinyInteger('emailcontrol_status')->default(1);
            $table->tinyInteger('emailcontrol_support')->default(1);
            $table->tinyInteger('emailcontrol_final')->default(1);
            $table->tinyInteger('active')->default(1);
            $table->tinyInteger('wholesale_options')->default(0);
            $table->tinyInteger('hide_al_lenderclient')->default(1);
            $table->tinyInteger('add_ihouse_proptypes')->default(0);
        });


        foreach ($clients as $key => $value) {
            foreach ($value as $field => $data) {
                if ($field != 'id') {
                    if ($data == "Y") {
                        $id = $value['id'];
                        DB::update("update user_groups set $field= 1 where id = $id");
                    } elseif ($data == "N") {
                        $id = $value['id'];
                        DB::update("update user_groups set $field= 0 where id = $id");
                    } elseif ($data == "") {
                        $id = $value['id'];
                        DB::update("update user_groups set $field= '' where id = $id");
                    }

                }

            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_groups', function (Blueprint $table) {
            //
        });
    }
}
