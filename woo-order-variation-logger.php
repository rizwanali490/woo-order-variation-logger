<?php
/**
 * Plugin Name: WooCommerce Order Variation Logger
 * Description: Logs variation data for each item before WooCommerce creates the order.
 * Version: 1.0
 * Author: Rizwan
 * Author URI: https://rizwandevs.com
 */

if (!defined('ABSPATH')) exit;

// Create log folder on plugin activation
register_activation_hook(__FILE__, function () {
    $log_dir = plugin_dir_path(__FILE__) . 'logs/';
    if (!file_exists($log_dir)) {
        mkdir($log_dir, 0755, true);
    }
});

add_action('woocommerce_checkout_create_order_line_item', function ($item, $cart_item_key, $values, $order) {
    $log_dir  = plugin_dir_path(__FILE__) . 'logs/';
    $log_file = $log_dir . 'variation-log-' . date('Y-m-d') . '.log';

    $log_data = [
        'timestamp'      => current_time('mysql'),
        'order_id'       => $order->get_id(),
        'product_id'     => $values['product_id'] ?? null,
        'variation_id'   => $values['variation_id'] ?? null,
        'attributes'     => $values['variation'] ?? [],
        'item_name'      => $item->get_name(),
        'cart_item_data' => $values
    ];

    file_put_contents($log_file, print_r($log_data, true) . "\n\n", FILE_APPEND);
}, 10, 4);
