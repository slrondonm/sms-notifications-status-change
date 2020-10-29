<?php
namespace Virtualizate\SMSNotifications;
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://virtualizate.com.co
 * @since      0.5.7
 *
 * @package    Sms_Notification
 * @subpackage Sms_Notification/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.5.7
 * @package    Sms_Notification
 * @subpackage Sms_Notification/includes
 * @author     Sergio Rondón <soporte@virtualizate.com.co>
 */
class i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.5.7
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			SMS_NOTIFICATIONS_SLUG,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
