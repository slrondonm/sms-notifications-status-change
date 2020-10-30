<?php
namespace Virtualizate\SMSNotifications;

/**
 * 
 * 
 */

use Virtualizate\SMSNotifications\i18n;
use Virtualizate\SMSNotifications\HablameSMS\Api;

class App
{
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.6.1
	 * @access   protected
	 * @var 	 Loader 	$loader    Maintains and registers all hooks for the plugin.
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

		self::load_dependencies();
		self::set_locale();
	}

	public static function run()
	{
		self::$loader->run();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sms_Notification_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.5.7
	 * @access   private
	 */
	private static function set_locale() {

		$plugin_i18n = new i18n();
		self::$loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	private static function load_dependencies()
	{
		require_once SMS_NOTIFICATIONS_PATH . 'includes/functions.php';

		$api_send = new Api();

		$api_send;

		add_filter('woocommerce_settings_tabs_array', [self::class, 'add_settings_tab'], 50);
		add_action('woocommerce_settings_tabs_settings_tab_virtualizate', [self::class, 'settings_tab']);
		add_action('woocommerce_update_options_settings_tab_virtualizate', [self::class, 'update_settings']);

		add_action('woocommerce_order_status_pending', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_pending'], 10, 1);
        add_action('woocommerce_order_status_failed', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_failed'], 10, 1);
        add_action('woocommerce_order_status_on-hold', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_on_hold'], 10, 1);
        add_action('woocommerce_order_status_processing', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_processing'], 10, 1);
        add_action('woocommerce_order_status_completed', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_completed'], 10, 1);
        add_action('woocommerce_order_status_refunded', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_refunded'], 10, 1);
        add_action('woocommerce_order_status_cancelled', [$api_send, 'virtualizate_send_customer_sms_for_woo_order_status_cancelled'], 10, 1);

		/*
         * Send new order admin SMS
         */
		add_action('woocommerce_order_status_processing',[$api_send, 'virtualizate_send_admin_sms_for_woo_new_order'], 10, 1);
		
		self::$loader = new Loader();
	}

	/**
	 * Undocumented function
	 *
	 * @param [type] $settings_tabs
	 * @return void
	 */
	public static function add_settings_tab($settings_tabs)
	{
		$settings_tabs['settings_tab_virtualizate'] = __('Virtualizate SMS', self::get_sms_notifications());
		return $settings_tabs;
	}

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function update_settings() {
        woocommerce_update_options(self::get_fields());
    }

	/**
	 * Undocumented function
	 *
	 * @return void
	 */
	public function settings_tab() {
        woocommerce_admin_fields(self::get_fields());
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
			'title'         => __('Enable SMS notifications for these customer actions', self::get_sms_notifications()),
			'desc'          => __('Pending', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_pending'),
			'default'       => 'yes',
			'desc_tip'      => __('Order received (unpaid)', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => 'start'
		);

		$fields[] = array(
			'desc'          => __('Failed', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_failed'),
			'default'       => 'yes',
			'desc_tip'      => __('Payment failed or was declined (unpaid)', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Processing', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_processing'),
			'default'       => 'yes',
			'desc_tip'      => __('Payment received', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Completed', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_completed'),
			'default'       => 'yes',
			'desc_tip'      => __('Order fulfilled and complete', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);

		$fields[] = array(
			'desc'          => __('On-Hold', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_on-hold'),
			'default'       => 'yes',
			'desc_tip'      => __('Order received (unpaid)', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);


		$fields[] = array(
			'desc'          => __('Cancelled', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_cancelled'),
			'default'       => 'yes',
			'desc_tip'      => __('Cancelled by an admin or the customer', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => '',
			'autoload'      => false
		);
		$fields[] = array(
			'desc'          => __('Refunded', self::get_sms_notifications()),
			'id'            => self::get_prefix('send_sms_refunded'),
			'default'       => 'yes',
			'desc_tip'      => __('Refunded by an admin', self::get_sms_notifications()),
			'type'          => 'checkbox',
			'checkboxgroup' => 'end',
			'autoload'      => false
		);


		$fields[] = array(
			'title'    => 'Default Message',
			'id'       => self::get_prefix('default_sms_template'),
			'desc_tip' => __('This message will be sent by default if there are no any text in the following event message fields.', self::get_sms_notifications()),
			'default'  => __('Your order #{{num_pedido}} is now {{status_pedido}}. Thank you for shopping at {{tienda}}.', self::get_sms_notifications()),
			'type'     => 'textarea',
			'css'      => 'min-width:500px;'
		);

		$fields[] = array(
			'title' => __('Pending Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('pending_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Failed Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('failed_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);

		$fields[] = array(
			'title' => __('Processing Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('processing_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Completed Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('completed_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('On-Hold Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('on-hold_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Cancelled Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('cancelled_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);
		$fields[] = array(
			'title' => __('Refund Message', self::get_sms_notifications()),
			'id'    => self::get_prefix('refunded_sms_template'),
			'css'   => 'min-width:500px;',
			'type'  => 'textarea'
		);

		/*
		 * Admin notifications
		 */

		$fields[] = array('type' => 'sectionend', 'id' => self::get_sms_notifications() . 'adminsettings');
		$fields[] = array(
			'title' => 'Notification for Admin',
			'type'  => 'title',
			'desc'  => 'Enable admin notifications for new customer orders.',
			'id'    => self::get_prefix('adminsettings')
		);

		$fields[] = array(
			'title'   => __('Receive Admin Notifications for New Orders.', self::get_sms_notifications()),
			'id'      => self::get_prefix('enable_admin_sms'),
			'default' => 'no',
			'type'    => 'checkbox'
		);
		$fields[] = array(
			'title'    => __('Admin Mobile Number', self::get_sms_notifications()),
			'id'       => self::get_prefix('admin_sms_recipients'),
			'desc_tip' => __('Enter admin mobile number begining with your country code.(e.g. 3XXXXXXXXX).', self::get_sms_notifications()),
			'default'  => '',
			'type'     => 'text'
		);
		$fields[] = array(
			'title'    => __('Message', self::get_sms_notifications()),
			'id'       => self::get_prefix('admin_sms_template'),
			'desc_tip' => __('Customization tags for new order SMS: {{tienda}}, {{num_pedido}}, {{total_pedido}}. 160 Characters.', self::get_sms_notifications()),
			'css'      => 'min-width:500px;',
			'default'  => 'You have a new customer order for {{tienda}}. Order #{{num_pedido}}, Total Value: {{total_pedido}}',
			'type'     => 'textarea'
		);

		/*
		 * API Credentials
		 */

		$fields[] = array('type' => 'sectionend', 'id' => self::get_sms_notifications() . 'apisettings');
		$fields[] = array(
			'title' => __('Virtualizate SMS Settings', self::get_sms_notifications()),
			'type'  => 'title',
			'desc'  => __('Provide following details from your Virtualizate SMS account. <a href="https://virtualizate.com.co" target="_blank">Click here</a> to go to API KEY section.', self::get_sms_notifications()),
			'id'    => self::get_sms_notifications() . 'virtualizate_settings'
		);

		$fields[] = array(
			'title'    => __('User ID', self::get_sms_notifications()),
			'id'       => self::get_prefix('account'),
			'desc_tip' => __('User id available in your Virtualizate account settings page.', self::get_sms_notifications()),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		$fields[] = array(
			'title'    => __('API Key', self::get_sms_notifications()),
			'id'       => self::get_prefix('api_key'),
			'desc_tip' => __('API key available in your Virtualizate account.', self::get_sms_notifications()),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		$fields[] = array(
			'title'    => __('Token', self::get_sms_notifications()),
			'id'       => self::get_prefix('token'),
			'desc_tip' => __('Enter your Virtualizate Token.', self::get_sms_notifications()),
			'type'     => 'text',
			'css'      => 'min-width:300px;',
		);
		/*
		  $fields[] = array(
		  'desc'    => __('Use if experiencing issues.', self::get_sms_notifications()),
		  'title'   => __('Log Api Errors', self::get_sms_notifications()),
		  'id'      => self::get_prefix('log_errors'),
		  'default' => 'no',
		  'type'    => 'checkbox'
		  );
		 */
		$fields[] = array('type' => 'sectionend', 'id' => self::get_sms_notifications() . 'customersettings');


		/*
		 * Shortcodes and its descriptions.
		 */
		
		$avbShortcodes = array(
			'{{nombre_cliente}}'   => __("First name of the customer.", self::get_sms_notifications()),
			'{{apellido_cliente}}' => __("Last name of the customer.", self::get_sms_notifications()),
			'{{tienda}}'           => __('Your shop name.', self::get_sms_notifications()) . '(' . get_bloginfo('name') . ')',
			'{{num_pedido}}'       => __('Ther order ID', self::get_sms_notifications()),
			'{{total_pedido}}'     => __("Current order amount", self::get_sms_notifications()),
			'{{status_pedido}}'    => __('Current order status (Pending, Failed, Processing, etc...)', self::get_sms_notifications()),
			'{{telefono_cliente}}' => __('Customer mobile number. (If given)', self::get_sms_notifications())
		);

		$fields[] = array(
			'title' => __('Available Shortcodes', self::get_sms_notifications()),
			'type'  => 'title',
			'desc'  => __('These shortcodes can be used in your message body.', self::get_sms_notifications()),
			'id'    => self::get_prefix('virtualizate_settings')
		);

		foreach ($avbShortcodes as $handle => $description) {
			$fields[] = array(
				'title' => $handle,
				'desc'  => $description,
				'type'  => 'text',
				'css'   => 'display:none;',
			);
		}
		$fields[] = array('type' => 'sectionend', 'id' => self::get_sms_notifications() . 'apisettings');
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
		$prefix = $before . SMS_NOTIFICATIONS_PREFIX . $after;
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
