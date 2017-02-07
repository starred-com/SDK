<?php

namespace StarredSdk;

/**
 * The invitation model represents the invitation endpoints of our API
 */
final class Invitation extends Model
{

    /**
     * Send an invitation to a single recipient
     *
     * @param int    $form             Form ID
     * @param int    $template         Template ID
     * @param string $templateLanguage Template iso language code (i.e. 'en')
     * @param string $sender           Sender email address
     * @param string $recipient        Recipient email address
     * @param string $firstName        Recipient first name
     * @param string $lastName         Recipient last name
     * @param bool   $reminder         Whether to send a reminder (defaults to true)
     * @param int    $batch            Batch ID (defaults to false)
     * @return array
     */
    public function singleRecipient(
        int     $form,
        int     $template,
        string  $templateLanguage,
        string  $sender,
        string  $recipient,
        string  $firstName,
        string  $lastName,
        bool    $reminder = true,
        int     $batch = 0
    ): array {
        return $this->send(
            'sendinvitations',
            [
                'form'      => (int) $form,
                'template'  => (int) $template,
                'language'  => $templateLanguage,
                'from'      => $sender,
                'recipient' => $recipient,
                'firstName' => $firstName,
                'lastName'  => $lastName,
                'reminder'  => (int) $reminder, // Convert reminder to 0 (false) or 1 (true)
                'batch'     => (int) $batch
            ]
        );
    }
}
