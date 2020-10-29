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
    protected static $loader;

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected static $sms_notifications;

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

        self::get_sms_notifications();
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function load_dependencies()
    {
    } 

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function run()
    {
        
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function get_loader()
    {
        self::$loader;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function get_version()
    {
        return self::$version;
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function get_sms_notifications()
    {
        self::$sms_notifications = SMS_NOTIFICATIONS_SLUG;
    }
}
