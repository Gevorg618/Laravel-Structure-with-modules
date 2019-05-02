<?php

namespace Modules\Admin\Repositories\Ticket;

use App\Models\Ticket\Content;
use Modules\Admin\Contracts\Ticket\TicketContentContract;

class TicketContentRepository implements TicketContentContract
{
    const CONTENT_TYPE_HTML = 'html';
    const CONTENT_TYPE_TEXT = 'text';

    /**
     * TicketContentRepository constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param int $ticketId
     * @param string $type
     * @return mixed
     */
    public function getContent($ticketId, $type)
    {
        $ticketContent = $this->getContentByType($ticketId, $type);
        $content = null;

        if ($type == self::CONTENT_TYPE_HTML && !$ticketContent) {
            $ticketContent = $this->getContentByType($ticketId, self::CONTENT_TYPE_TEXT);
        }

        if ($ticketContent) {
            $content = $ticketContent->content;

            if ($type == self::CONTENT_TYPE_TEXT) {
                $content = nl2br($content);
            } else {
                $content = preg_replace('/<meta\s.*?\/>/is', '', $content);
                $content = preg_replace('~<head.*?>(.|\n)*?</head>~', '', $content);
                $content = preg_replace('#(<br */?>\s*){3,}#i', '<br />', $content);
                $content = preg_replace('#(<br */?>\s*)#i', '<p></p>', $content);
                $content = preg_replace('/[^(\x20-\x7F)]*/', '', $content);
                $content = preg_replace('/[^[:print:]]/', '', $content);
            }
        }

        return $content;
    }

    /**
     * @param int $ticketId
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getContentByType($ticketId, $type)
    {
        return Content::where([['ticket_id', '=', $ticketId], ['type', '=', $type]])->first();
    }

    /**
     * @param int $ticketId
     * @param string $type
     * @param string $content
     */
    public function addTicketContent($ticketId, $type, $content)
    {
        Content::create([
            'ticket_id' => $ticketId,
            'type' => $type,
            'content' => $content
        ]);
    }
}