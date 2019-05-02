<?php

namespace Modules\Admin\Services\Integrations;

use App\Models\Tools\Setting;
use Modules\Admin\Helpers\StringHelper;
use Modules\Admin\Services\Ticket\TicketModerationService;

class GoogleService
{
    protected $log = [];
    protected $client = null;
    protected $moderation;
    protected $service;

    /**
     * GoogleService constructor.
     * @param TicketModerationService $moderation
     */
    public function __construct(TicketModerationService $moderation)
    {
        $this->client = $this->getClient();
        $this->moderation = $moderation;
        $this->service = new \Google_Service_Gmail($this->client);

        $this->refresh();
    }

    /**
     * @param int $max
     * @param string $label
     * @param string $query
     * @return array
     */
    public function getEmails($max = 10, $label = '', $query = '')
    {
        $options = ['maxResults' => $max];

        if ($label) {
            $options['labelIds'] = $label;
        }

        if ($query) {
            $options['q'] = $query;
        }

        $list = $this->service->users_messages->listUsersMessages('me', $options);

        return [
            'list' => $list,
            'messages' => $this->processEmails($list->getMessages())
        ];
    }

    /**
     * @param $messageId
     * @return mixed
     */
    public function getEmailData($messageId)
    {
        $emailCollection = collect([['id' => $messageId]]);
        $result = $this->processEmails($emailCollection);
        return reset($result);
    }

    /**
     * @param $code
     * @return array
     */
    public function setAuthCode($code)
    {
        $refreshToken = $this->client->authenticate($code);
        // Save setting
        $this->setRefreshToken($refreshToken);
        return $refreshToken;
    }

    /**
     * Revoke access token
     */
    public function revoke()
    {
        $this->client->revokeToken($this->client->getAccessToken());
        $this->setRefreshToken('');
    }

    /**
     * Refresh access token
     */
    public function refresh()
    {
        // Refresh token
        $refreshToken = $this->client->getRefreshToken();
        if ($refreshToken) {
            $this->client->refreshToken($refreshToken);
        }

        $accessToken = $this->client->getAccessToken();
        if ($accessToken) {
            $this->setRefreshToken($accessToken);
        }
    }

    /**
     * @return int
     */
    public function countTodayEmails()
    {
        $query = sprintf(
            'after:%s before:%s',
            date('Y/m/d', strtotime('today')),
            date('Y/m/d', strtotime('tomorrow'))
        );

        $emails = $this->service->users_messages->listUsersMessages('me', ['q' => $query]);

        return $emails->getResultSizeEstimate();
    }

    /**
     * @param string|array $token
     */
    protected function setRefreshToken($token)
    {
        if (is_array($token)) {
            $token = json_encode($token);
        }

        Setting::where('setting_key', 'google_api_refresh_token')->update([
            'value' => $token,
            'last_update_date' => time()
        ]);

        Setting::resetCache();
    }

    /**
     * @return array|null
     */
    public function getToken()
    {
        return $this->client->getAccessToken();
    }

    /**
     * @return bool
     */
    public function isClientValid()
    {
        return $this->client->getAccessToken() && !$this->client->isAccessTokenExpired();
    }

    /**
     * @return bool
     */
    public function isTokenExpired()
    {
        return $this->client->isAccessTokenExpired();
    }

    /**
     * @return string
     */
    public function getAuthUrl()
    {
        return $this->client->createAuthUrl();
    }

    /**
     * @param array $accessToken
     * @return string
     */
    public function createTicketsFromEmailBox($accessToken = [])
    {
        if ($accessToken) {
            $this->client->setAccessToken($accessToken);
        }
        if (!$this->checkCredentials()) {
            return $this->getLog();
        }

        $inBoxes = ['INBOX', 'SPAM'];

        foreach ($inBoxes as $inbox) {
            // Load current emails in the mailbox and spam

            $inboxEmails = $this->service->users_messages->listUsersMessages('me', [
                'maxResults' => 50, 'labelIds' => $inbox
            ]);

            $this->addLog('Processing ' . $inbox);
            $this->addLog('Found ' . count($inboxEmails) . ' Emails');

            if ($inboxEmails) {
                $emails = $this->processEmails($inboxEmails, [], false, true, true);

                if ($emails) {
                    foreach ($emails as $email) {
                        $this->addLog('Importing: ' . $email['messageSubject']);

                        $log = $this->moderation->createTicketFromEmail($email);

                        $this->addLog($log);
                        $this->addLog();
                    }
                }
            }
        }

        return $this->getLog();
    }

    /**
     * @return string
     */
    public function getLog()
    {
        return implode("\n", $this->log);
    }

    /**
     * @return \Google_Client
     */
    public function getClient()
    {
        $client = new \Google_Client();

        $client->setApplicationName(config('app.site_name'));
        $client->setClientId(Setting::getSetting('google_api_client_id'));
        $client->setClientSecret(Setting::getSetting('google_api_secret'));
        $client->setRedirectUri(url(config('services.google.redirect_url')));

        $client->setScopes([\Google_Service_Gmail::MAIL_GOOGLE_COM]);
        $client->setIncludeGrantedScopes(true);
        $client->setAccessType('offline');
        $client->setApprovalPrompt('force');

        return $client;
    }

    /**
     * @param $emails
     * @param array $hasLabels
     * @param bool $trash
     * @param bool $parseAttachments
     * @param bool $manage
     * @return array
     */
    protected function processEmails($emails, $hasLabels = [], $trash = false, $parseAttachments = false, $manage = false)
    {
        $inboxMessages = [];

        foreach ($emails as $email) {
            $message = $this->service->users_messages->get('me', $email->id, ['format' => 'full', 'id' => $email->id]);

            $messagePayload = $message->getPayload();
            $parts = $message->getPayload()->getParts();
            $labels = $message->getLabelIds();
            $attachments = [];

            // Skip the ones that are read
            if ($hasLabels && count($hasLabels)) {
                foreach ($hasLabels as $hasLabel) {
                    if (!in_array($hasLabel, $labels)) {
                        continue 2;
                    }
                }
            }

            $decodedTextMessage = null;
            $decodedHtmlMessage = null;

            foreach ($parts as $part) {
                $data = $this->getPartData($email->id, $part, $parseAttachments);

                $decodedTextMessage = $data['textMessage'] ?? null;
                $decodedHtmlMessage = $data['htmlMessage'] ?? null;

                // Attachments
                $attachments = $data['attachments'] ?? [];
            }

            // Fix missing parts but body in payload exists
            if (!count($parts) && $messagePayload->getBody()) {
                $sanitizedData = StringHelper::sanitizeStr($messagePayload->getBody()->getData());
                $decodedHtmlMessage = base64_decode($sanitizedData);
            }

            $to = [];
            $sender = [];
            $cc = [];

            foreach ($message->getPayload()->getHeaders() as $header) {
                if ($header->getName() == 'Subject') {
                    $subject = $header->getValue();
                } else if ($header->getName() == 'Date') {
                    $date = $header->getValue();
                    $unix = strtotime($date);
                    $date = date('M jS Y h:i A', $unix);
                } else if ($header->getName() == 'From') {
                    $sender = $this->normalizeEmails($header->getName(), $header->getValue());
                } else if ($header->getName() == 'CC') {
                    $cc = $this->normalizeEmails($header->getName(), $header->getValue());
                } else if ($header->getName() == 'To') {
                    $to = $this->normalizeEmails($header->getName(), $header->getValue());
                }
            }

            $inboxMessages[] = [
                'messageId' => $email->id,
                'messageSnippet' => $message->getSnippet(),
                'messageSubject' => $subject ?? '(No Subject)',
                'messageDate' => $date ?? null,
                'messageUnixDate' => $unix ?? null,
                'messageSender' => $sender,
                'messageTo' => $to,
                'messageCc' => $cc,
                'messageTextBody' => $decodedTextMessage,
                'messageHtmlBody' => $decodedHtmlMessage,
                'attachments' => $attachments,
            ];

            if ($manage) {
                $mods = new \Google_Service_Gmail_ModifyMessageRequest();
                $mods->setRemoveLabelIds(['UNREAD', 'INBOX', 'SPAM', 'IMPORTANT', 'STARRED']);

                $this->service->users_messages->modify('me', $email->id, $mods);

                // Trash Messages
                if ($trash) {
                    $this->service->users_messages->trash('me', $email->id);
                }
            }
        }

        return $inboxMessages;
    }

    /**
     * @param $messageId
     * @param $part
     * @param $parseAttachments
     * @return array
     */
    protected function getPartData($messageId, \Google_Service_Gmail_MessagePart $part, $parseAttachments)
    {
        $data = [];

        $mimeType = $part->getMimeType();
        $filename = $part->getFilename();
        $subParts = $part->getParts();

        if ($mimeType == 'text/plain') {
            $data['textMessage'] = StringHelper::sanitizeStr($part->getBody()->getData());
        } elseif ($mimeType == 'text/html') {
            $data['htmlMessage'] = StringHelper::sanitizeStr($part->getBody()->getData());;
        } elseif ($mimeType == 'multipart/alternative' || $mimeType == 'multipart/related') {
            foreach ($subParts as $subPart) {
                $data = array_merge($data, $this->getPartData($messageId, $subPart, $parseAttachments));
            }
        }

        if ($filename) {
            $attachmentBody = null;
            $attachmentId = $part->getBody()->getAttachmentId();

            if ($parseAttachments) {
                $attachmentData = $this->service->users_messages_attachments->get('me', $messageId, $attachmentId);
                $attachmentBody = StringHelper::sanitizeStr($attachmentData->getData());
            }

            $data['attachments'][] = [
                'id' => $attachmentId,
                'filename' => $filename,
                'mimetype' => $mimeType,
                'body' => $attachmentBody
            ];
        }

        return $data;
    }

    /**
     * @param $type
     * @param $input
     * @return array
     */
    protected function normalizeEmails($type, $input)
    {
        $input = str_replace('"', '', $input);
        if ($type == 'CC') {
            $input = explode(',', $input);
        } else {
            $input = [$input];
        }

        $return = [];

        foreach ($input as $row) {
            preg_match_all('/\<(.*)\>/', $row, $matches);

            if ($matches && !empty($matches[1])) {
                foreach ($matches[0] as $id => $match) {
                    $email = $matches[1][$id];
                    $return[] = ['email' => $email, 'name' => ''];
                }
            }
        }

        return $return;
    }

    /**
     * @return bool
     */
    protected function checkCredentials()
    {
        if (!Setting::getSetting('google_api_enable')) {
            $this->addLog('Google API is not turned on.');
        }

        if (!Setting::getSetting('google_api_client_id')) {
            $this->addLog('Google API Client ID is not set.');
        }

        if (!Setting::getSetting('google_api_secret')) {
            $this->addLog('Google API Secret is not set.');
        }

        // Make sure token is valid
        if (empty($this->log) && !$this->isClientValid()) {
            $error = 'Sorry, The client has no valid auth token. please initiate the auto';
            $error .= ' through the Google API page in the Admin Dashboard.';
            $this->addLog($error);
        }

        return empty($this->log);
    }

    /**
     * @param string $log
     */
    protected function addLog($log = "\n-------------------------")
    {
        if (is_array($log)) {
            $this->log = array_merge($this->log, $log);
        } else {
            $this->log[] = $log;
        }
    }
}