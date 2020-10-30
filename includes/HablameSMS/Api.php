<?php
namespace Virtualizate\SMSNotifications\HablameSMS;

use Virtualizate\SMSNotifications\App;

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http:   //virtualizate.com.co
 * @since      0.5.7
 *
 * @package    Sms_Notification
 * @subpackage Sms_Notification/includes/services
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.5.7
 * @package    Sms_Notification
 * @subpackage Sms_Notification/includes/services
 * @author     Sergio Rondón <soporte@virtualizate.com.co>
 */

class Api
{
    /**
     * Undocumented variable
     *
     * @var string
     */
    private static $account, $apiKey, $token, $adminRecipients; //número de usuario / clave API del usuario / Token de usuario

    /**
     * Undocumented variable
     *
     * @var string
     */
    private static $yesPending, $yesOnHold, $yesProcessing, $yesCompleted, $yesCancelled, $yesRefunded, $yesFailed, $yesAdminMsg;
    
    /**
     * Undocumented variable
     *
     * @var [type]
     */
    private static $contentDefault, $contentPending, $contentOnHold, $contentProcessing, $contentCompleted, $contentCancelled, $contentRefunded, $contentFailed, $contentAdmin;


    public function __construct()
    {
        self::$account = get_option(App::get_prefix('account'));
        self::$apiKey  = get_option(App::get_prefix('api_key'));
        self::$token   = get_option(App::get_prefix('token'));

        self::$yesPending    = get_option(App::get_prefix('send_sms_pending'));
        self::$yesOnHold     = get_option(App::get_prefix('send_sms_on-hold'));
        self::$yesProcessing = get_option(App::get_prefix('send_sms_processing'));
        self::$yesCompleted  = get_option(App::get_prefix('send_sms_completed'));
        self::$yesCancelled  = get_option(App::get_prefix('send_sms_cancelled'));
        self::$yesRefunded   = get_option(App::get_prefix('send_sms_refunded'));
        self::$yesFailed     = get_option(App::get_prefix('send_sms_failed'));

        self::$contentDefault = get_option(App::get_prefix('default_sms_template'));
        self::$contentPending = get_option(App::get_prefix('pending_sms_template'));
        self::$contentOnHold = get_option(App::get_prefix('on-hold_sms_template'));
        self::$contentProcessing = get_option(App::get_prefix('processing_sms_template'));
        self::$contentCompleted = get_option(App::get_prefix('completed_sms_template'));
        self::$contentCancelled = get_option(App::get_prefix('cancelled_sms_template'));
        self::$contentRefunded = get_option(App::get_prefix('refunded_sms_template'));
        self::$contentFailed = get_option(App::get_prefix('failed_sms_template'));
        
        self::$yesAdminMsg   = get_option(App::get_prefix('enable_admin_sms'));
        self::$contentAdmin = get_option(App::get_prefix('admin_sms_template'));
    }

    public static function virtualizate_send_admin_sms_for_woo_new_order($order_id) {
        if (self::$yesAdminMsg)
            self::virtualizate_send($order_id, 'admin-order');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_pending($order_id) {
        if (self::$yesPending)
            self::virtualizate_send($order_id, 'pending');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_failed($order_id) {
        if (self::$yesFailed)
            self::virtualizate_send($order_id, 'failed');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_on_hold($order_id) {
        if (self::$yesOnHold)
            self::virtualizate_send($order_id, 'on-hold');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_processing($order_id) {
        if (self::$yesProcessing) {
            self::virtualizate_send($order_id, 'processing');
        }
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_completed($order_id) {
        if (self::$yesCompleted)
            self::virtualizate_send($order_id, 'completed');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_refunded($order_id) {
        if (self::$yesRefunded)
            self::virtualizate_send($order_id, 'refunded');
    }

    public static function virtualizate_send_customer_sms_for_woo_order_status_cancelled($order_id) {
        if (self::$yesCancelled)
            self::virtualizate_send($order_id, 'cancelled');
    }

    public static function send_api($to_number, $message)
    {
        $ch = curl_init();

        $post = array(
            'account'           => self::$account,   //número de usuario
            'apiKey'            => self::$apiKey,    //clave API del usuario
            'token'             => self::$token,     // Token de usuario
            'toNumber'          => $to_number,       //número de destino
            'sms'               => $message,         // mensaje de texto
            'flash'             => '0',              //mensaje tipo flash
            'sendDate'          => time(),           //fecha de envío del mensaje
            'isPriority'        => 0,                //mensaje prioritario
            'sc'                => '899991',         //código corto para envío del mensaje de texto
            'request_dlvr_rcpt' => 0,                //mensaje de texto con confirmación de entrega al celular
        );
     
        $url = "https://api101.hablame.co/api/sms/v2.1/send/";  //endPoint: Primario
    
        curl_setopt ($ch,CURLOPT_URL,$url) ;
        curl_setopt ($ch,CURLOPT_POST,1);
        curl_setopt ($ch,CURLOPT_POSTFIELDS, $post);
        curl_setopt ($ch,CURLOPT_RETURNTRANSFER, true);
        curl_setopt ($ch,CURLOPT_CONNECTTIMEOUT ,3);
        curl_setopt ($ch,CURLOPT_TIMEOUT, 20);
        $response = curl_exec($ch);
        curl_close($ch);
        $response = json_decode($response ,true) ;

        if ($response["status"]== '1x000' ){
            throw new \Exception( esc_html__('El SMS se ha enviado exitosamente con el ID: '.$response["smsId"].PHP_EOL));
        } else {
            throw new \Exception( esc_html__('Ha ocurrido un error: '.$response["error_description"].'('.$response ["status" ]. ')'. PHP_EOL));
        }
    }


    public static function virtualizate_send($order_id, $status)
    {
        $order_details = new \WC_Order($order_id);
        $message       = '';
        switch ($status) {
            case 'pending': 
                $message = self::$contentPending;
                break;
            case 'failed': 
                $message = self::$contentFailed;
                break;
            case 'on-hold': 
                $message = self::$contentOnHold;
                break;
            case 'processing': 
                $message = self::$contentProcessing;
                break;
            case 'completed': 
                $message = self::$contentCompleted;
                break;
            case 'refunded': 
                $message = self::$contentRefunded;
                break;
            case 'cancelled': 
                $message = self::$contentCancelled;
                break;
            case 'admin-order': 
                $message = self::$contentAdmin;
                break;
            default: 
                $message = self::$contentDefault;
                break;
        }

        $message = (empty($message) ? self::$contentDefault : $message);
        $message = short_code($message, $order_details);
        $phone      = ('admin-order' === $status ? self::$adminRecipients : $order_details->billing_phone);
        //$phone   = format_number($pn);

        self::send_api($phone, $message);
    }


}

// $to_number = '3506736502';
// $message = get_option('_sms_order_completed');
// $sendMessage = new SMS_Gateway_Hablame();
// $sendMessage->send($to_number, $message);