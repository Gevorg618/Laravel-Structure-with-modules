<?php

namespace Modules\Admin\Services\Ticket;

use Carbon\Carbon;

use App\Models\Ticket\Rule;
use App\Models\Ticket\RuleAction;
use App\Models\Ticket\RuleCondition;

class TicketRuleService
{
    /**
     * TicketRuleService constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param \Illuminate\Http\Request $request
     */
    public function create($request)
    {
        // Add new rule
        $rule = Rule::create($this->prepareRuleData($request, true));

        // Add action
        $rule->action()->create($this->prepareActionData($request));

        // Add conditions
        if ($request['conditions']) {
            foreach ($request['conditions'] as $id => $condition) {
                RuleCondition::create($this->prepareConditionData($condition, $rule->id));
            }
        }
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Ticket\Rule $rule
     */
    public function update($request, $rule)
    {
        // Update rule
        $rule->update($this->prepareRuleData($request));

        // Update action
        $rule->action()->update($this->prepareActionData($request));

        // Update conditions
        $rule->conditions()->delete();
        if ($request['conditions']) {
            foreach ($request['conditions'] as $id => $condition) {
                RuleCondition::create($this->prepareConditionData($condition, $rule->id));
            }
        }
    }

    /**
     * @param \App\Models\Ticket\Rule $rule
     */
    public function delete($rule)
    {
        // Delete conditions
        $rule->conditions()->delete();

        // Delete action
        $rule->action()->delete();

        // Delete rule
        $rule->delete();
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param bool $new
     * @return array
     */
    protected function prepareRuleData($request, $new = false)
    {
        $data = [
            'title' => $request['title'],
            'description' => $request['description'],
            'match_type' => $request['match_type'],
            'is_active' => $request['is_active'],
        ];

        if ($new) {
            $data['created_date'] = Carbon::now()->timestamp;
            $data['created_by'] = admin()->id;
        }

        return $data;
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    protected function prepareActionData($request)
    {
        return [
            'public_comment' => $request['public_comment'],
            'reply' => $request['reply'],
            'reply_all' => $request['reply_all'],
            'close_or_open' => $request['close_or_open'],
            'assign_to' => $request['assign_to'],
            'set_status' => $request['set_status'],
            'set_category' => $request['set_category'],
            'set_priority' => $request['set_priority'],
            'assign_order' => $request['assign_order'],
            'comment' => $request['reply_text'],
            'multi_mod' => $request['multi_mod'],
            'add_participants' => isset($request['participants'])
                ? implode(',', $request['participants'])
                : '',
        ];
    }

    /**
     * @param array $condition
     * @param int $ruleId
     * @return array
     */
    protected function prepareConditionData($condition, $ruleId)
    {
        $value = null;
        if ($condition['condition_key'] == 'category') {
            $value = $condition['condition_category'];
        } elseif ($condition['condition_value']) {
            $value = $condition['condition_value'];
        }

        return [
            'rule_id' => $ruleId,
            'condition_key' => $condition['condition_key'],
            'condition_match_type' => $condition['condition_match_type'],
            'condition_value' => $value,
        ];
    }
}