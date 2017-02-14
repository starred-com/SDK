<?php

namespace StarredSdkTest\System;

require_once \realpath(__DIR__ . '/../../App/Autoload.php');

use PHPUnit\Framework\TestCase;

/**
 * Invitation model system test
 *
 * This system test covers the basic sending routines based on your
 * configuration. This will send real invitations so be careful ;)
 *
 * @note This is an actual system test so it _will_ send you an invite
 *
 * @coversNothing
 */
class InvitationTest extends TestCase
{

    /**
     * @var array
     */
    static private $settings;

    /**
     * @var array
     */
    static private $auth;

    /**
     * Initialise StarredSdk autoloader before setup
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        new \StarredSdk\Autoload();

        // Get config files
        self::$settings = \parse_ini_file(\realpath(__DIR__ . '/../Config/Invitation.ini'));
        self::$auth = \parse_ini_file(\realpath(__DIR__ . '/../Config/Auth.ini'));
    }

    /**
     * Initialise invitation model for all tests
     *
     * @return void
     */
    public function setUp()
    {
        // Initialise Invitation model
        $this->invitation = new \StarredSdk\Invitation(
            new \StarredSdk\Config\Auth(self::$auth),
            null,
            self::$settings['host']
        );
    }

    /**
     * Send a single invitation
     *
     * @test
     */
    public function sendNewSingleInvitation()
    {

        $result = $this->invitation->singleRecipient(
            self::$settings['form'],
            self::$settings['template'],
            self::$settings['templateLanguage'],
            self::$settings['sender'],
            self::$settings['recipient'],
            self::$settings['firstName'],
            self::$settings['lastName'],
            self::$settings['reminder']
        );

        // Assert the invitation request has been created
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('ok', $result['status']);
    }

    /**
     * Send a single invitation to an existing batch
     *
     * @test
     */
    public function sendSingleInvitationToExistingBatch()
    {

        $result = $this->invitation->singleRecipient(
            self::$settings['form'],
            self::$settings['template'],
            self::$settings['templateLanguage'],
            self::$settings['sender'],
            self::$settings['recipient'],
            self::$settings['firstName'],
            self::$settings['lastName'],
            self::$settings['reminder'],
            self::$settings['existingBatch']
        );

        // Assert the invitation request has been created
        $this->assertArrayHasKey('status', $result);
        $this->assertSame('ok', $result['status']);
    }

    /**
     * Send a single invitation with an invalid template
     *
     * @test
     */
    public function sendSingleInvitationInvalidTemplate()
    {
        $result = $this->invitation->singleRecipient(
            self::$settings['form'],
            123456,
            self::$settings['templateLanguage'],
            self::$settings['sender'],
            self::$settings['recipient'],
            self::$settings['firstName'],
            self::$settings['lastName'],
            self::$settings['reminder']
        );

        // Assert the invitation request has been created
        $this->assertArrayHasKey('errorCode', $result);
        $this->assertSame(400, $result['errorCode']);
    }

    /**
     * Send a single invitation with an sender (Should be a registered email
     * address which is also part of the company)
     *
     * @test
     */
    public function sendSingleInvitationInvalidSender()
    {
        $result = $this->invitation->singleRecipient(
            self::$settings['form'],
            self::$settings['template'],
            self::$settings['templateLanguage'],
            'foo@bar.com',
            self::$settings['recipient'],
            self::$settings['firstName'],
            self::$settings['lastName'],
            self::$settings['reminder']
        );

        // Assert the invitation request has been created
        $this->assertArrayHasKey('errorCode', $result);
        $this->assertSame(400, $result['errorCode']);
    }
}
