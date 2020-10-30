<?php
/**
 * 
 * 
 */

function format_number($value)
{
    $number = preg_replace("/[^0-9]/", '', $value);
    if (strlen($number) == 9) {
        $number = $number;
    } elseif (strlen($number) == 10 && substr($number, 0, 1) == '0') {
        $number = ltrim($number, "0");
    } elseif (strlen($number) == 12 && substr($number, 0, 3) == '940') {
        $number = ltrim($number, "940");
    }
    return $number;
}

function short_code($message, $order_details)
{
    $placeholders = [
        '{{tienda}}' => get_bloginfo('name'),
        '{{num_pedido}}' => $order_details->get_order_number(),
        '{{total_pedido}}' => $order_details->get_total(),
        '{{status_pedido}}' => ucfirst($order_details->get_status()),
        '{{nombre_cliente}}' => ucfirst($order_details->billing_first_name),
        '{{apellido_cliente}}' => ucfirst($order_details->billing_last_name),
        '{{telefono_cliente}}' => $order_details->billing_phone,
    ];

    return str_replace(array_keys($placeholders), $placeholders, $message);
}