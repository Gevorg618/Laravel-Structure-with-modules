<?php

namespace App\Models\Ticket;

use App\Models\BaseModel;

class RuleCondition extends BaseModel
{
    const CONDITION_FROM_ADDRESS = 'from_address';
    const CONDITION_TO_ADDRESS = 'to_address';
    const CONDITION_CC_ADDRESS = 'cc_address';
    const CONDITION_SUBJECT = 'subject';
    const CONDITION_BODY = 'body';
    const CONDITION_HAS_ATTACHMENTS = 'has_attachments';
    const CONDITION_NO_ATTACHMENTS = 'no_attachments';
    const CONDITION_CATEGORY = 'category';

    const MATCH_TYPE_CONTAINS = 'contains';
    const MATCH_TYPE_DOES_NOT_CONTAIN = 'does_not_contain';
    const MATCH_TYPE_BEGINS_WITH = 'begins_with';
    const MATCH_TYPE_ENDS_WITH = 'ends_with';
    const MATCH_TYPE_EQUAL_TO = 'equal_to';
    const MATCH_TYPE_REGEX = 'regex';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tickets_rule_condition';

    protected $fillable = [
        'rule_id',
        'condition_key',
        'condition_match_type',
        'condition_value',
    ];

    public $timestamps = false;

    public static function getConditionKeysMatched()
    {
        $keys = self::getConditions();
        $list = [];
        foreach ($keys as $id => $i) {
            if ($i['show_match_type']) {
                $list[$id] = $id;
            }
        }
        return $list;
    }

    public static function getConditionKeys()
    {
        $keys = self::getConditions();
        $list = [];
        foreach ($keys as $id => $i) {
            $list[$id] = $i['title'];
        }
        return $list;
    }

    public static function getConditions()
    {
        return [
            self::CONDITION_FROM_ADDRESS => [
                'title' => 'From Email Address',
                'show_match_type' => true
            ],
            self::CONDITION_TO_ADDRESS => [
                'title' => 'To Email Address',
                'show_match_type' => true
            ],
            self::CONDITION_CC_ADDRESS => [
                'title' => 'CC Email Address(es)',
                'show_match_type' => true
            ],
            self::CONDITION_SUBJECT => [
                'title' => 'Subject',
                'show_match_type' => true
            ],
            self::CONDITION_BODY => [
                'title' => 'Body',
                'show_match_type' => true
            ],
            self::CONDITION_HAS_ATTACHMENTS => [
                'title' => 'Has Attachments',
                'show_match_type' => false
            ],
            self::CONDITION_NO_ATTACHMENTS => [
                'title' => 'No Attachments',
                'show_match_type' => false
            ],
            self::CONDITION_CATEGORY => [
                'title' => 'Category',
                'show_match_type' => true
            ],
        ];
    }

    public static function getConditionMatchTypes()
    {
        return [
            self::MATCH_TYPE_CONTAINS => 'Contains',
            self::MATCH_TYPE_DOES_NOT_CONTAIN => 'Does Not Contain',
            self::MATCH_TYPE_BEGINS_WITH => 'Begins With',
            self::MATCH_TYPE_ENDS_WITH => 'Ends With',
            self::MATCH_TYPE_EQUAL_TO => 'Is Equal To',
            self::MATCH_TYPE_REGEX => 'Regex',
        ];
    }
}
