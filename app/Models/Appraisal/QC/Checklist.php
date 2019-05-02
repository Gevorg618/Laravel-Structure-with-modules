<?php

namespace App\Models\Appraisal\QC;


use App\Models\Customizations\LoanReason;
use App\Models\Customizations\LoanType;
use App\Models\Customizations\Type;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;

/**
 * Class Checklist
 * @package App\Models\Appraisal\QC
 */
class Checklist extends \Eloquent
{
    /**
     * @var string
     */
    protected $table = 'appr_qc_checklist';

    /**
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = ['ord', 'is_active'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(
            Client::class,
            'appr_qc_checklist_client',
            'rel_id',
            'client_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function lenders()
    {
        return $this->belongsToMany(
            UserGroupLender::class,
            'appr_qc_checklist_lender',
            'rel_id',
            'lender_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanTypes()
    {
        return $this->belongsToMany(
            UserGroupLender::class,
            'appr_qc_checklist_loan_type',
            'rel_id',
            'loan_type_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanReasons()
    {
        return $this->belongsToMany(
            LoanReason::class,
            'appr_qc_checklist_loan_reason',
            'rel_id',
            'loan_reason_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appraisalTypes()
    {
        return $this->belongsToMany(
            Type::class,
            'appr_qc_checklist_appr_type',
            'rel_id',
            'appr_type_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function children()
    {
        return $this->hasMany(self::class, 'parent_question', 'id');
    }

    /**
     *
     */
    public function removeRelations()
    {
        $this->clients()->detach();
        $this->lenders()->detach();
        $this->loanTypes()->detach();
        $this->loanReasons()->detach();
        $this->appraisalTypes()->detach();
    }

    /**
     * @param array $request
     * @return bool
     */
    public function saveRelations($request = [])
    {
        if (isset($request['clients'])) {
            $this->clients()->attach($request['clients']);
        }
        if (isset($request['lenders'])) {
            $this->lenders()->attach($request['lenders']);
        }
        if (isset($request['appraisal_type'])) {
            $this->appraisalTypes()->attach($request['appraisal_type']);
        }
        if (isset($request['loan_type'])) {
            $this->loanTypes()->attach($request['loan_type']);
        }
        if (isset($request['loan_reason'])) {
            $this->loanReasons()->attach($request['loan_reason']);
        }
        return true;
    }

    public function setData($request = [])
    {
        $this->title = $request['title'];
        $this->qc_correction = $request['qc_correction'] ?? '';
        $this->qc_client_correction = $request['client_correction'] ?? '';
        $this->category = $request['category'] ?? 0;
        $this->realview_rule_id = $request['realview_rule_id'];
        $this->parent_question = $request['parent_question'] ?? 0;
        $this->is_active = $request['is_active'];
        $this->is_required = $request['is_required'];
        return $this;
    }
}
