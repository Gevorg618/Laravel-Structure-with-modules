<?php

namespace Modules\Admin\Repositories\Management;


use App\Models\Appraisal\QC\Checklist;
use Illuminate\Support\Collection;

/**
 * Class ChecklistRepository
 * @package Modules\Admin\Repositories
 */
class ChecklistRepository
{
    /**
     * @return array
     */
    public function getCategoriesQuestionsCount()
    {
        return Checklist::select(\DB::raw('COUNT(id) as total, category'))
            ->where('is_deleted', 0)
            ->groupBy('category')->pluck('total', 'category')->toArray();
    }

    /**
     * @return static
     */
    public function getCategoryQuestions()
    {
        return Checklist::with([
            'loanTypes',
            'loanReasons',
            'appraisalTypes'
        ])->where('is_deleted', 0)->orderBy('ord')->get()
            ->groupBy('category');
    }

    /**
     * @param $category
     * @return array
     */
    public function makeParentQuestionsDropdown($category)
    {
        $result = [];
        $list = Checklist::with('children')->where('is_deleted', 0)
            ->where('category', $category)
            ->where('parent_question', 0)->get()->keyBy('id');
        foreach ($list as $item) {
            $result[$item->id] = $item->title;
            foreach ($item->children as $child) {
                $result[] = '---' . $child->title;
            }
        }
        return $result;
    }

    /**
     * @param $clientIds
     * @return static
     */
    public function getClientSpecificQuestions($clientIds)
    {
        return Checklist::with([
            'clients',
            'loanTypes',
            'loanReasons',
            'appraisalTypes',
        ])->whereHas('clients', function ($query) use ($clientIds) {
            return $query->whereIn('appr_qc_checklist_client.client_id', $clientIds);
        })->orderBy('ord')->get()->groupBy('clients.*.id');
    }

    /**
     * @param $lenderIds
     * @return static
     */
    public function getLenderSpecificQuestions($lenderIds)
    {
        return Checklist::with([
            'lenders',
            'loanTypes',
            'loanReasons',
            'appraisalTypes',
        ])->whereHas('lenders', function ($query) use ($lenderIds) {
            return $query->whereIn('appr_qc_checklist_lender.lender_id', $lenderIds);
        })->orderBy('ord')->get()->groupBy('lenders.*.id');
    }

    /**
     * @return Collection
     */
    public function getLenders()
    {
        return Checklist::with('lenders')->where('category', 0)
            ->orderBy('ord')->get()->pluck('lenders')->flatten();
    }

    /**
     * @return Collection
     */
    public function getClients()
    {
        return Checklist::with('clients')->where('category', 0)
            ->orderBy('ord')->get()->pluck('clients')->flatten();
    }
}
