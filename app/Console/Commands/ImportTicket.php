<?php

namespace App\Console\Commands;

use App\Models\Tools\Setting;
use Illuminate\Console\Command;
use Modules\Admin\Services\Ticket\TicketModerationService;
use Modules\Admin\Services\Integrations\GoogleService;

/**
 * Class ImportTicket
 * @package App\Console\Commands
 */
class ImportTicket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:import-tickets {--manual}';

    protected $ticketModerationService;

    protected $debug = true;

    /**
     * @var \Google_Client $client
     */
    protected $client;

    protected $googleService;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reads a Gmail account and imports all emails (inbox/spam) and creates tickets.';

    /**
     * ImportTicket constructor.
     * @param TicketModerationService $service
     * @param GoogleService $googleService
     * Create a new command instance.
     */
    public function __construct(TicketModerationService $service, GoogleService $googleService)
    {
        Setting::resetCache();
        parent::__construct();
        $this->ticketModerationService = $service;
        $this->googleService = $googleService;
        $this->client = $this->googleService->getClient();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $start = microtime(true);
        $this->info('Start');

        $accessToken = [];
        $refreshToken = Setting::getSetting('google_api_refresh_token');
        if ($refreshToken) {
            $accessToken = $this->client->fetchAccessTokenWithRefreshToken($refreshToken);
            if (!isset($accessToken['error'])) {
                $this->client->setAccessToken($accessToken);
            }
        }
        if (!$this->client->getAccessToken()) {
            if (!$this->option('manual')) {
                \Log::error('Invalid refresh token in DB');
                return false;
            }
            $authUrl = $this->client->createAuthUrl();
            $this->info(printf("Open the following link in your browser:\n%s\n", $authUrl));
            $code = $this->secret('Enter verification code: ');
            $accessToken = $this->client->fetchAccessTokenWithAuthCode($code);
            $this->client->setAccessToken($accessToken);
            Setting::where('setting_key', 'google_api_refresh_token')->update([
                'value' => $accessToken['refresh_token'],
                'last_update_date' => time()
            ]);

            Setting::resetCache();
        }
        $this->info($this->googleService->createTicketsFromEmailBox($accessToken));

        $end = microtime(true) - $start;
        $this->output->success(sprintf(
            'Done. Time Took: %s s.', number_format($end, 3)));
        return true;
    }
}
