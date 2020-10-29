<?php
/**
 * Plugin Name: SMS Notificatons in Status Change for WooCoomerce
 * Plugin URI: https://virtualizate.com.co/
 * Description: @todo.
 * Version: 0.1.0
 * Author: Grupo Virtualizate
 * Author URI: https://virtualizate.com.co/
 * Developer: Ing Sergio L. Rondon M.
 * Developer URI: https://github.com/slrondonm/
 * Text Domain: sms-notifications-status-change
 * Domain Path: /languages
 *
 * WC requires at least: 4.4
 * WC tested up to: 4.5.2
 *
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

//use Virtualizate\SMSNotifications\Core\App;

if (!defined('ABSPATH')) {
    exit;
}

define('SMS_NOTIFICATIONS_VERSION', '0.6.1');
define('SMS_NOTIFICATIONS_SLUG', 'sms-notifications-status-change');
define('SMS_NOTIFICATIONS_PREFIX', 'snsc');
define('SMS_NOTIFICATIONS_CAPABILITY', 'manage_options');
define('SMS_NOTIFICATIONS_URL', stripslashes(plugin_dir_url(__FILE__)));
define('SMS_NOTIFICATIONS_PATH', plugin_dir_path(__FILE__));

if ( file_exists( SMS_NOTIFICATIONS_PATH . 'vendor/autoload.php' ) ) {
  require( SMS_NOTIFICATIONS_PATH . 'vendor/autoload.php' );
}

if ( file_exists( SMS_NOTIFICATIONS_PATH . 'includes/functions.php' ) ) {
    require( SMS_NOTIFICATIONS_PATH . 'includes/functions.php' );
  }

function activate_sms_notifications()
{
    \Virtualizate\SMSNotifications\Activator::activate();
}

function deactivate_sms_notifications()
{
    \Virtualizate\SMSNotifications\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_sms_notifications' );
register_deactivation_hook(__FILE__, 'deactivate_sms_notifications');

\Virtualizate\SMSNotifications\App::run();