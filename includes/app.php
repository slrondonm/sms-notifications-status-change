<?php
namespace Virtualizate\SMSNotifications;

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

    public function run()
    {
        # code...
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    public static function load_dependencies()
    {

    } 

    public function get_fields()
    {
        $fields[] = [
            'title' => __('Notifications for customer', self::get_sms_notifications()),
            'type' => 'title',
            'desc' => __('Send SMS to customer\'s mobile phone. Will be sent to the phone number which customer is providing while checkout process.', self::get_sms_notifications()),
            'id' => self::get_prefix('customer_settings_title')
        ];

        $fields[] = array(
            'title' => 'Enable SMS notifications for these customer actions',
            'desc' => 'Pending',
            'id' => $this->prefix . 'send_sms_pending',
            'default' => 'yes',
            'desc_tip' => __('Order received (unpaid)', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => 'start'
        );

        $fields[] = array(
            'desc' => __('Failed', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_failed',
            'default' => 'yes',
            'desc_tip' => __('Payment failed or was declined (unpaid)', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Processing', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_processing',
            'default' => 'yes',
            'desc_tip' => __('Payment received', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Completed', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_completed',
            'default' => 'yes',
            'desc_tip' => __('Order fulfilled and complete', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );

        $fields[] = array(
            'desc' => __('On-Hold', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_on-hold',
            'default' => 'yes',
            'desc_tip' => __('Order received (unpaid)', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );


        $fields[] = array(
            'desc' => __('Cancelled', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_cancelled',
            'default' => 'yes',
            'desc_tip' => __('Cancelled by an admin or the customer', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => '',
            'autoload' => false
        );
        $fields[] = array(
            'desc' => __('Refunded', TEXTDOMAIN),
            'id' => $this->prefix . 'send_sms_refunded',
            'default' => 'yes',
            'desc_tip' => __('Refunded by an admin', TEXTDOMAIN),
            'type' => 'checkbox',
            'checkboxgroup' => 'end',
            'autoload' => false
        );


        $fields[] = array(
            'title' => 'Default Message',
            'id' => $this->prefix . 'default_sms_template',
            'desc_tip' => __('This message will be sent by default if there are no any text in the following event message fields.', TEXTDOMAIN),
            'default' => __('Your order #{{order_id}} is now {{order_status}}. Thank you for shopping at {{shop_name}}.', TEXTDOMAIN),
            'type' => 'textarea',
            'css' => 'min-width:500px;'
        );

        $fields[] = array(
            'title' => __('Pending Message', TEXTDOMAIN),
            'id' => $this->prefix . 'pending_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Failed Message', TEXTDOMAIN),
            'id' => $this->prefix . 'failed_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );

        $fields[] = array(
            'title' => __('Processing Message', TEXTDOMAIN),
            'id' => $this->prefix . 'processing_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Completed Message', TEXTDOMAIN),
            'id' => $this->prefix . 'completed_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('On-Hold Message', TEXTDOMAIN),
            'id' => $this->prefix . 'on-hold_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Cancelled Message', TEXTDOMAIN),
            'id' => $this->prefix . 'cancelled_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
        $fields[] = array(
            'title' => __('Refund Message', TEXTDOMAIN),
            'id' => $this->prefix . 'refunded_sms_template',
            'css' => 'min-width:500px;',
            'type' => 'textarea'
        );
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
    * Append a field prefix as defined in $config
    *
    * @param string $field_name The string/field to prefix
    * @param string $before String to add before the prefix
    * @param string $after String to add after the prefix
    * @return string Prefixed string/field value
    * @since 0.1.0
    */
  public static function get_prefix( $field_name = null, $before = '', $after = '_' ) {

    $prefix = $before . SMS_NOTIFICATION_PREFIX . $after;
    return $field_name !== null ? $prefix . $field_name : $prefix;

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
