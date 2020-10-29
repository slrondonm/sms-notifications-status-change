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

		self:: get_sms_notifications();
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

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function get_fields()	{
		
		$fields[] = array(
			'title' => __('Notifications for customer', self::get_sms_notifications()),
			'type'  => 'title',
			'desc'  => __('Send SMS to customer\'s mobile phone. Will be sent to the phone number which customer is providing while checkout process.', self::get_sms_notifications()),
			'id'    => self::get_prefix('customer_settings_title')
		);

		$fields[] = array(
			'title'         => 'Enable SMS notifications for these customer actions',
			'desc'          => 'Pending',
			'id'            => self::get_prefix('send_sms_pending'),
			'default'       => 'yes',
			'desc_tip'      => __('Order received (unpaid)', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => 'start'
		);

		$fields[] = array(
			'desc'          => __('Failed', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_failed'),
			'default'       => 'yes',
			'desc_tip'      => __('Payment failed or was declined (unpaid)', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Processing', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_processing'),
			'default'       => 'yes',
			'desc_tip'      => __('Payment received', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Completed', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_completed'),
			'default'       => 'yes',
			'desc_tip'      => __('Order fulfilled and complete', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);

		$fields[] = array(
			'desc'          => __('On-Hold', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_on-hold'),
			'default'       => 'yes',
			'desc_tip'      => __('Order received (unpaid)', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);


		$fields[] = array(
			'desc'          => __('Cancelled', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_cancelled'),
			'default'       => 'yes',
			'desc_tip'      => __('Cancelled by an admin or the customer', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Refunded', TEXTDOMAIN),
			'id'            => self::get_prefix('send_sms_refunded'),
			'default'       => 'yes',
			'desc_tip'      => __('Refunded by an admin', TEXTDOMAIN),
			'type'          => 'checkbox',
			'checkboxgroup' => 'end',
			'autoload'      => false
		);


		$fields[] = array(
			'title'    => 'Default Message',
			'id'       => self::get_prefix('default_sms_template'),
			'desc_tip' => __('This message will be sent by default if there are no any text in the following event message fields.', TEXTDOMAIN),
			'default'  => __('Your order #{{order_id}} is now {{order_status}}. Thank you for shopping at {{shop_name}}.', TEXTDOMAIN),
			'type'     => 'textarea',
			'css'      => 'min-width:500px;'
		);

		$fields[] = array(
			'title' => __('Pending Message', TEXTDOMAIN),
			'id'    => self::get_prefix('pending_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Failed Message', TEXTDOMAIN),
			'id'    => self::get_prefix('failed_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);

		$fields[] = array(
			'title' => __('Processing Message', TEXTDOMAIN),
			'id'    => self::get_prefix('processing_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Completed Message', TEXTDOMAIN),
			'id'    => self::get_prefix('completed_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('On-Hold Message', TEXTDOMAIN),
			'id'    => self::get_prefix('on-hold_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Cancelled Message', TEXTDOMAIN),
			'id'    => self::get_prefix('cancelled_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Refund Message', TEXTDOMAIN),
			'id'    => self::get_prefix('refunded_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);

		/*
		 * Admin notifications
		 */

		$fields[] = array('type' => 'sectionend', 'id' => TEXTDOMAIN . 'adminsettings');
		$fields[] = array(
			'title' => 'Notification for Admin',
			'type'  => 'title',
			'desc'  => 'Enable admin notifications for new customer orders.',
			'id'    => TEXTDOMAIN . 'adminsettings'
		);

		$fields[] = array(
			'title'   => 'Receive Admin Notifications for New Orders.',
			'id'      => self::get_prefix('enable_admin_sms'),
			'default' => 'no',
			'type'    => 'checkbox'
		);
		$fields[] = array(
			'title'    => 'Admin Mobile Number',
			'id'       => self::get_prefix('admin_sms_recipients'),
			'desc_tip' => 'Enter admin mobile number begining with your country code.(e.g. 9471XXXXXXXX).',
			'default'  => '',
			'type'     => 'text'
		);
		$fields[] = array(
			'title'    => 'Message',
			'id'       => self::get_prefix('admin_sms_template'),
			'desc_tip' => 'Customization tags for new order SMS: {{shop_name}}, {{order_id}}, {{order_amount}}. 160 Characters.',
			'css'      => 'min-width:500px;',
			'default'  => 'You have a new customer order for {{shop_name}}. Order #{{order_id}}, Total Value: {{order_amount}}',
			'type'     => 'textarea'
		);

		/*
		 * API Credentials
		 */

		$fields[] = array('type' => 'sectionend', 'id' => TEXTDOMAIN . 'apisettings');
		$fields[] = array(
			'title' => __('Virtualizate SMS Settings', TEXTDOMAIN),
			'type'  => 'title',
			'desc'  => 'Provide following details from your Virtualizate SMS account. <a href="https://app.notify.lk/settings/api-keys" target="_blank">Click here</a> to go to API KEY section.',
			'id'    => TEXTDOMAIN . 'virtualizate_settings'
		);

		$fields[] = array(
			'title'    => __('User ID', TEXTDOMAIN),
			'id'       => self::get_prefix('account'),
			'desc_tip' => __('User id available in your NotifyLK account settings page.', TEXTDOMAIN),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		$fields[] = array(
			'title'    => __('API Key', TEXTDOMAIN),
			'id'       => self::get_prefix('api_key'),
			'desc_tip' => __('API key available in your NotifyLK account.', TEXTDOMAIN),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		$fields[] = array(
			'title'    => __('Token', TEXTDOMAIN),
			'id'       => self::get_prefix('token'),
			'desc_tip' => __('Enter your NotifyLK purchased SenderID.', TEXTDOMAIN),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		/*
		  $fields[] = array(
		  'desc'    => __('Use if experiencing issues.', TEXTDOMAIN),
		  'title'   => __('Log Api Errors', TEXTDOMAIN),
		  'id'      => self::get_prefix('log_errors'),
		  'default' => 'no',
		  'type'    => 'checkbox'
		  );
		 */
		$fields[] = array('type' => 'sectionend', 'id' => TEXTDOMAIN . 'customersettings');


		/*
		 * Shortcodes and its descriptions.
		 */
		
		$avbShortcodes = array(
			'{{nombre_cliente}}'   => "First name of the customer.",
			'{{apellido_cliente}}' => "Last name of the customer.",
			'{{tienda}}'           => 'Your shop name.('.get_bloginfo('name').')',
			'{{num_pedido}}'       => 'Ther order ID',
			'{{total_pedido}}'     => "Current order amount",
			'{{status_pedido}}'    => 'Current order status (Pending, Failed, Processing, etc...)',
			'{{telefono_cliente}}' => 'Customer mobile number. (If given)'
		);

		$fields[] = array(
			'title' => __('Available Shortcodes', TEXTDOMAIN),
			'type'  => 'title',
			'desc'  => 'These shortcodes can be used in your message body.',
			'id'    => TEXTDOMAIN . 'notifylk_settings'
		);

		foreach ($avbShortcodes as $handle => $description) {
			$fields[] = array(
				'title' => __($handle, TEXTDOMAIN),
				'desc'  => __($description, TEXTDOMAIN),
				'type'  => 'text',
				'css'   => 'display:none;',
			);
		}
		$fields[] = array('type' => 'sectionend', 'id' => TEXTDOMAIN . 'apisettings');
		return $fields;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function get_loader()
	{
		self:: $loader;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public static function get_version()
	{
		return self:: $version;
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
		return $field_name != null ? $prefix . $field_name : $prefix;

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
