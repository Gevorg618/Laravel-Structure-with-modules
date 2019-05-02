<?php

namespace Modules\Admin\Services\Ticket;

use App\Models\Management\EmailTemplate;
use App\Models\Management\UserTemplate;
use App\Models\Appraisal\Order;
use App\Models\Tools\Setting;

class TemplateService
{
    /**
     * @param int $templateId
     * @return string
     */
    public function getTemplate($templateId)
    {
        if (strpos($templateId, 'u_') !== false) { // If id has 'u_' it's a user template
            $id = str_replace('u_', '', $templateId);
            $template = UserTemplate::find($id);
        } else {
            $template = EmailTemplate::find($templateId);
        }

        return $template->content;
    }

    /**
     * @return array
     */
    public function getTemplateList()
    {
        $templates = EmailTemplate::orderBy('category', 'asc')->orderBy('title', 'asc')->get();

        $list = [];

        $userTemplates = UserTemplate::where([
            ['user_id', '=', admin()->id],
            ['is_approved', '=', 1],
        ])->orderBy('title', 'asc')->get();

        if ($userTemplates->count()) {
            $list['My Templates'] = $userTemplates->pluck('title', 'id');
        }

        $emailCategories = [
            'client' => 'Clients',
            'appr' => 'Appraisers',
            'sales' => 'Sales',
        ];

        if ($templates->count()) {
            foreach ($templates as $item) {
                $category = $emailCategories[$item->category] ?? config('constants.not_available');

                $list[$category][$item->id] = $item->title;
            }
        }

        return $list;
    }

    // ---------------------

    /**
     * @param string $template
     * @param int $orderId
     * @return string
     */
    public function bindParams($template, $orderId)
    {
        $order = Order::find($orderId);

        if ($order) {
            $template = $order->convertKeys($template);
        }

        return $template;
    }
}