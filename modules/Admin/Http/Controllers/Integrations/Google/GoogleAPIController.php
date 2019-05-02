<?php

namespace Modules\Admin\Http\Controllers\Integrations\Google;

use App\Models\Ticket\Ticket;
use Illuminate\Http\Request;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Http\Controllers\AdminBaseController;
use Modules\Admin\Http\Requests\Integrations\Google\OAuthCallbackRequest;
use Modules\Admin\Http\Requests\Integrations\Google\SearchEmailRequest;
use Modules\Admin\Repositories\Ticket\TicketRepository;
use Modules\Admin\Services\Integrations\GoogleService;

class GoogleAPIController extends AdminBaseController
{
    protected $client;

    protected $ticketRepository;

    /**
     * GoogleAPIController constructor.
     * @param GoogleService $client
     */
    public function __construct(GoogleService $client, TicketRepository $repository)
    {
        $this->client = $client;
        $this->ticketRepository = $repository;
    }

    /**
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin::integrations.google.index', [
            'client' => $this->client,
            'ticketsCount' => $this->ticketRepository->getTicketsCount(),
        ]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchEmail(SearchEmailRequest $request)
    {
        $emails = $this->client->getEmails(50, '', $request->term);

        $html = view('admin::integrations.google.templates._emails', [
            'emails' => $emails
        ])->render();

        return response()->json(['html' => $html]);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function viewEmailMessage(Request $request)
    {
        try {
            $data = $this->client->getEmailData($request->id);
            if ($data) {
                $data['messageTextBody'] = StringHelper::normalizeEmailMessage($data['messageTextBody']);
                if (!$data['messageTextBody'] && $data['messageHtmlBody']) {
                    $data['messageTextBody'] = StringHelper::normalizeEmailMessage($data['messageHtmlBody']);
                }
            }

            $response = $data['messageHtmlBody'];

        } catch (\Exception $e) {
            $response = ['error' => 'Sorry, we could not fetch that email'];
        }

        return response()->json($response);
    }

    /**
     * @param OAuthCallbackRequest $request
     */
    public function oauthCallback(OAuthCallbackRequest $request)
    {
         // Exchange authorization code for an access token
        $this->client->setAuthCode($request->code);
    }

    /**
     * Revoke access token after AJAX call
     * @return \Illuminate\Http\RedirectResponse
     */
    public function revoke()
    {
        try {
            $this->client->revoke();

            return redirect()->route('admin.integrations.google')
                ->with('success', 'Auth Code Revoked.');

        } catch (\Exception $e) {
            return redirect()->route('admin.integrations.google')
                ->with('error', 'Sorry, There was an error. ' . $e->getMessage());
        }
    }

    /**
     * Refresh access token after AJAX call
     * @return \Illuminate\Http\RedirectResponse
     */
    public function refresh()
    {
        try {
            $this->client->refresh();

            return redirect()->route('admin.integrations.google')
                ->with('success', 'Auth Code Refreshed.');

        } catch (\Exception $e) {
            return redirect()->route('admin.integrations.google')
                ->with('error', 'Sorry, There was an error. ' . $e->getMessage());
        }
    }
}
