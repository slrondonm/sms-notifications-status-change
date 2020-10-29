<?php
namespace SMSNotifications\Core;
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
    private $account; //número de usuario

    /**
     * Undocumented variable
     *
     * @var string
     */
	private $apiKey; //clave API del usuario
    
    /**
     * Undocumented variable
     *
     * @var string
     */
    private $token; // Token de usuario

    function __construct()
    {
        $this->account = get_option('_sms_account');
        $this->apiKey  = get_option('_sms_api_key');
        $this->token   = get_option('_sms_token');
    }

    public function send($to_number, $message)
    {
        $ch = curl_init();

        $post = array(
            'account'           => $this->account,   //número de usuario
            'apiKey'            => $this->apiKey,    //clave API del usuario
            'token'             => $this->token,     // Token de usuario
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

        // if ($response["status"]== '1x000' ){
        //     throw new Exception( esc_html__('El SMS se ha enviado exitosamente con el ID: '.$response["smsId"].PHP_EOL));
        // } else {
        //     throw new Exception( esc_html__('Ha ocurrido un error: '.$response["error_description"].'('.$response ["status" ]. ')'. PHP_EOL));
        // }
    }
}

// $to_number = '3506736502';
// $message = get_option('_sms_order_completed');
// $sendMessage = new SMS_Gateway_Hablame();
// $sendMessage->send($to_number, $message);