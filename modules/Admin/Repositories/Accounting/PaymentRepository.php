<?php

namespace Modules\Admin\Repositories\Accounting;


use App\Models\Accounting\AlCimCheckPayment;
use App\Models\Accounting\AlCimPayment;
use App\Models\Appraisal\AppraiserPayment;
use App\Models\Appraisal\ApprCimPayment;
use App\Models\Appraisal\ApprFDPayment;
use App\Models\Accounting\CimCheckPayment;
use App\Models\Integrations\MercuryNetwork\MercuryCharge;

/**
 * Class PaymentRepository
 * @package Modules\Admin\Repositories
 */
class PaymentRepository
{
    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getApprChecksByTerm($term)
    {
        $term = '%' . $term;
        return CimCheckPayment::with('user')->where('check_number', 'like', $term)
            ->orWhere('check_from', 'like', $term)
            ->latest('created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getALChecksByTerm($term)
    {
        $term = '%' . $term . '%';
        return AlCimCheckPayment::where('check_number', 'like', $term)
            ->orWhere('check_from', 'like', $term)
            ->latest('created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getApprCardsByTerm($term)
    {
        $term = '%' . $term . '%';
        return ApprCimPayment::with('user')
            ->select(\DB::raw("
                    appr_cim_payments.*, 
                p.credit_number, 
                p.zipcode, 
                p.card_name, 
                p.cvv, 
                p.credit_type
            "))->leftJoin(
                'appr_cim_profile as p',
                'p.cim_profile_id',
                '=',
                'appr_cim_payments.cim_profile_id'
            )->where(function ($query) use ($term) {
                return $query->where('appr_cim_payments.trans_id', 'like', $term)
                    ->orWhere('p.credit_number', 'like', $term)
                    ->orWhere('p.card_name', 'like', $term);
            })->where('appr_cim_payments.is_success', 1)
            ->where('appr_cim_payments.is_void', 0)
            ->latest('appr_cim_payments.created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getFDApprCardsByTerm($term) {
        $term = '%' . $term . '%';
        return ApprFDPayment::with('user')
            ->select(\DB::raw("
                appr_fd_payments.*, 
                p.credit_number, 
                p.zipcode, 
                p.card_name, 
                p.cvv, 
                p.credit_type
            "))->leftJoin(
                'appr_fd_profile as p',
                'p.id',
                '=',
                'appr_fd_payments.fd_profile_id'
            )->where(function ($query) use ($term) {
                return $query->where('appr_fd_payments.auth_code', 'like', $term)
                    ->orWhere('p.credit_number', 'like', $term)
                    ->orWhere('p.card_name', 'like', $term);
            })->where('appr_fd_payments.is_success', 1)
            ->where('appr_fd_payments.is_void', 0)
            ->latest('created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getALCardsByTerm($term) {
        $term = '%' . $term . '%';
        return AlCimPayment::with('user')
            ->select(\DB::raw("
                al_cim_payments.*, 
                p.credit_number, 
                p.zipcode, 
                p.card_name, 
                p.cvv, 
                p.credit_type
            "))->leftJoin(
                'al_cim_profile as p',
                'p.cim_profile_id',
                '=',
                'al_cim_payments.cim_profile_id'
            )->where(function ($query) use ($term) {
                return $query->where('al_cim_payments.trans_id', 'like', $term)
                    ->orWhere('p.credit_number', 'like', $term)
                    ->orWhere('p.card_name', 'like', $term);
            })->where('al_cim_payments.is_success', 1)
            ->where('al_cim_payments.is_void', 0)
            ->latest('created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getMercuryTsysPayments($term) {
        $term = '%' . $term . '%';
        return MercuryCharge::with('user')->where(function ($query) use ($term) {
            return $query->where('card_holder_name', 'like', $term)
                ->orWhere('cc_last_four', 'like', $term)
                ->orWhere('expiration_date', 'like', $term)
                ->orWhere('card_holder_address', 'like', $term);
        })->where('is_success', 1)
            ->where('transaction_type', 'charge')
            ->latest('created_date')->get();
    }

    /**
     * @param $term
     * @return \Illuminate\Database\Eloquent\Collection|\Illuminate\Support\Collection|static[]
     */
    public function getApprCheckPaymentsSent($term) {
        $term = '%' . $term . '%';
        return AppraiserPayment::with('user')->where('checknum', 'like', $term)
            ->latest('paid')->get();
    }
}