<?php
namespace Virtualizate\SMSNotifications\Core;
/**
 * 
 * 
 */
class App
{
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected static $sms_notification;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected static $version;

    /**
     * Undocumented function
     */
    public function __construct()
    {
        if (!defined('SMS_NOTIFICATIONS_VERSION')) {
            self::$version = "1.0.0";
        } else {
            self::$version = SMS_NOTIFICATIONS_VERSION;
        }

        $this->get_sms_notifications();
    }

    public static function run()
    {
        
    }

    public function get_version()
    {
        return self::$version;
    }

    public function get_sms_notifications()
    {
        self::$sms_notification = SMS_NOTIFICATIONS_SLUG;
        return self::$sms_notification;
    }
}
