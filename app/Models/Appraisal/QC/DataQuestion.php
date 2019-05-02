<?php

namespace App\Models\Appraisal\QC;

use App\Models\Customizations\LoanReason;
use App\Models\Customizations\LoanType;
use App\Models\Customizations\Type;
use App\Models\BaseModel;
use App\Models\Clients\Client;
use App\Models\Management\WholesaleLenders\UserGroupLender;
use App\Models\Users\User;
use Illuminate\Http\Request;

/**
 * Class DataQuestion
 * @package App\Models\Appraisal\QC
 */
class DataQuestion extends BaseModel
{
    /**
     * @var string
     */
    protected $table = 'appr_qc_data_collection_question';

    /**
     * @var bool
     */
    public $timestamps = false;


    public static function getAllQuestion()
    {
        return self::select('id', 'title')->get();
    }

    /**
     * @return $this
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by')->with('userData');
    }

    /**
     * @return array
     */
    public static function getFieldTypes()
    {
        return [
            'textfield' => 'Text Field',
            'textarea' => 'Text Area',
            'dropdown' => 'Dropdown',
            'checkbox' => 'Checkbox Button(s)',
            'radio' => 'Radio Button(s)',
            'date' => 'Date Selector',
            'datetime' => 'Date & Time Selector',
            'multi' => 'Multi Select Box',
            'yesno' => 'Yes/No Checkbox',
            'editor' => 'HTML Editor',
        ];
    }

    /**
     * @return array
     */
    public static function getFormats()
    {
        return [
            'text' => 'Text',
            'number' => 'Number',
            'currency' => 'Currency'
        ];
    }

    /**
     * @param Request $request
     * @return $this
     */
    public function setDataToModel(Request $request)
    {
        $this->title = $request->post('title');
        $this->description = $request->post('description');
        $this->pos = $request->post('pos');
        $this->format = $request->post('format');
        $this->is_active = $request->post('is_active');
        $this->is_required = $request->post('is_required');
        $this->field_type = $request->post('field_type');
        $this->field_extra = $request->post('field_extra');
        $this->default_value = $request->post('default_value');
        return $this;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanReasons()
    {
        return $this->belongsToMany(
            LoanReason::class,
            'appr_qc_data_collection_question_loan_reason',
            'question_id',
            'type_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function loanTypes()
    {
        return $this->belongsToMany(
            LoanType::class,
            'appr_qc_data_collection_question_loan_type',
            'question_id',
            'type_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function appraisalTypes()
    {
        return $this->belongsToMany(
            Type::class,
            'appr_qc_data_collection_question_appr_type',
            'question_id',
            'type_id'
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function clients()
    {
        return $this->belongsToMany(
            Client::class,
            'appr_qc_data_collection_question_client',
            'question_id',
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
            'appr_qc_data_collection_question_lender',
            'question_id',
            'lender_id'
        );
    }

    /**
     * @return $this
     */
    public function eraseRelations()
    {
        $this->loanReasons()->detach();
        $this->loanTypes()->detach();
        $this->appraisalTypes()->detach();
        $this->clients()->detach();
        $this->lenders()->detach();
        return $this;
    }

    /**
     * @param array $request
     * @return $this
     */
    public function saveRelations($request = [])
    {
        if (isset($request['loan_reason'])) {
            $this->loanReasons()->attach($request['loan_reason']);
        }
        if (isset($request['loan_type'])) {
            $this->loanTypes()->attach($request['loan_type']);
        }
        if (isset($request['appraisal_type'])) {
            $this->appraisalTypes()->attach($request['appraisal_type']);
        }
        if (isset($request['clients'])) {
            $this->clients()->attach($request['clients']);
        }
        if (isset($request['lenders'])) {
            $this->lenders()->attach($request['lenders']);
        }
        return $this;
    }
}
