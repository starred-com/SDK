<?php

// Define namespace
namespace StarredSdkTest\Unit;

require_once \realpath(__DIR__ . '/../../App/Model.php');
require_once \realpath(__DIR__ . '/../../App/Invitation.php');
require_once \realpath(__DIR__ . '/../../App/Curl/Request.php');
require_once \realpath(__DIR__ . '/../../App/Config/Auth.php');

use PHPUnit\Framework\TestCase;

/**
 * Curl request test case
 *
 * @coversDefaultClass \StarredSdk\Invitation
 * @covers ::<protected>
 */
class InvitationTest extends TestCase
{

    /**
     * Send a single invitation
     *
     * @covers ::singleRecipient
     * @test
     * @return void
     */
    public function singleRecipient()
    {

        $form = 1;
        $template = 2;
        $templateLanguage = 'en';
        $sender = 'sender@starred.com';
        $recipient = 'johndoe@example.com';
        $firstName = 'John';
        $lastName = 'Doe';
        $reminder = false;

        $requestMock = $this->getMockBuilder('\StarredSdk\Curl\Request')
            ->setMethods(
                ['setEndpoint', 'setRequestData', 'postRequest', 'getResponseAsArray']
            )
            ->getMock();
        $requestMock->expects($this->once())
            ->method('setEndpoint')
            ->with('https://api.starred.com/sendinvitations');
        $requestMock->expects($this->once())
            ->method('setRequestData')
            ->with(
                [
                    'company'   => 'COMPANY_KEY',
                    'auth'      => 'AUTH_KEY',
                    'form'      => $form,
                    'template'  => $template,
                    'language'  => $templateLanguage,
                    'from'      => $sender,
                    'recipient' => $recipient,
                    'firstName' => $firstName,
                    'lastName'  => $lastName,
                    'reminder'  => (int) $reminder,
                    'batch'     => false
                ]
            );
        $requestMock->expects($this->once())
            ->method('postRequest');
        $requestMock->expects($this->once())
            ->method('getResponseAsArray')
            ->will($this->returnValue(['status' => 'ok']));

        $invitation = new \StarredSdk\Invitation(
            new \StarredSdk\Config\Auth([
                'company' => 'COMPANY_KEY',
                'auth' => 'AUTH_KEY'
            ]),
            $requestMock
        );
        $result = $invitation->singleRecipient(
            $form,
            $template,
            $templateLanguage,
            $sender,
            $recipient,
            $firstName,
            $lastName,
            $reminder
        );

        $this->assertSame(['status' => 'ok'], $result);
    }
}
