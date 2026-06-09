<?php
/**
 * Plugin Name: WooCommerce Cart Extras
 * Description: Gift product, express shipping, hide special products.
 * Version: 1.0
 * Author: Maja
 */

if ( ! defined( 'ABSPATH' ) ) exit;

function display_gift_option() {
    $gift_price = 499;
    $gift_product_id = 28505;
    $is_gift_in_cart = false;
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( $cart_item['product_id'] == $gift_product_id ) {
            $is_gift_in_cart = true;
            break;
        }
    }
    echo '<div class="gift-option-container">';
    echo '<label>';
    echo '<input type="checkbox" id="add_gift_to_cart" ' . checked( $is_gift_in_cart, true, false ) . '>';
    echo ' Dodaj <strong>Proizvod iznenađenja</strong> za ' . wc_price( $gift_price );
    echo '</label></div>';
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#add_gift_to_cart').on('change', function() {
            var action = $(this).is(':checked') ? 'add_gift_to_cart' : 'remove_gift_from_cart';
            $.post('<?php echo esc_url( admin_url('admin-ajax.php') ); ?>', {
                action: action,
                product_id: <?php echo $gift_product_id; ?>
            }, function() { location.reload(); });
        });
    });
    </script>
    <?php
}
add_action('woocommerce_before_cart', 'display_gift_option');

add_action('wp_ajax_add_gift_to_cart', 'add_gift_to_cart');
add_action('wp_ajax_nopriv_add_gift_to_cart', 'add_gift_to_cart');
function add_gift_to_cart() {
    $product_id = intval($_POST['product_id']);
    if ( $product_id ) WC()->cart->add_to_cart( $product_id );
    wp_die();
}

add_action('wp_ajax_remove_gift_from_cart', 'remove_gift_from_cart');
add_action('wp_ajax_nopriv_remove_gift_from_cart', 'remove_gift_from_cart');
function remove_gift_from_cart() {
    $product_id = intval($_POST['product_id']);
    foreach ( WC()->cart->get_cart() as $key => $item ) {
        if ( $item['product_id'] == $product_id ) {
            WC()->cart->remove_cart_item( $key );
            break;
        }
    }
    wp_die();
}

function display_express_shipping_option() {
    $express_price = 299;
    $express_product_id = 28508;
    $is_express_in_cart = false;
    foreach ( WC()->cart->get_cart() as $cart_item ) {
        if ( $cart_item['product_id'] == $express_product_id ) {
            $is_express_in_cart = true;
            break;
        }
    }
    echo '<div class="express-shipping-container">';
    echo '<label>';
    echo '<input type="checkbox" id="add_express_to_cart" ' . checked( $is_express_in_cart, true, false ) . '>';
    echo ' Dodaj <strong>Ubrzanu dostavu i sigurno pakovanje</strong> za ' . wc_price( $express_price );
    echo '</label></div>';
    ?>
    <script>
    jQuery(document).ready(function($) {
        $('#add_express_to_cart').on('change', function() {
            var action = $(this).is(':checked') ? 'add_express_to_cart' : 'remove_express_from_cart';
            $.post('<?php echo esc_url( admin_url('admin-ajax.php') ); ?>', {
                action: action,
                product_id: <?php echo $express_product_id; ?>
            }, function() { location.reload(); });
        });
    });
    </script>
    <?php
}
add_action('woocommerce_after_cart', 'display_express_shipping_option');

add_action('wp_ajax_add_express_to_cart', 'add_express_to_cart');
add_action('wp_ajax_nopriv_add_express_to_cart', 'add_express_to_cart');
function add_express_to_cart() {
    $product_id = intval($_POST['product_id']);
    if ( $product_id ) WC()->cart->add_to_cart( $product_id );
    wp_die();
}

add_action('wp_ajax_remove_express_from_cart', 'remove_express_from_cart');
add_action('wp_ajax_nopriv_remove_express_from_cart', 'remove_express_from_cart');
function remove_express_from_cart() {
    $product_id = intval($_POST['product_id']);
    foreach ( WC()->cart->get_cart() as $key => $item ) {
        if ( $item['product_id'] == $product_id ) {
            WC()->cart->remove_cart_item( $key );
            break;
        }
    }
    wp_die();
}

add_filter('woocommerce_cart_item_quantity', 'hide_quantity_for_special_products', 10, 2);
function hide_quantity_for_special_products( $product_quantity, $cart_item_key ) {
    $hidden_ids = [28505, 28508, 28804, 28806, 28807, 28808, 28809];
    $cart_item = WC()->cart->get_cart()[ $cart_item_key ];
    if ( in_array( $cart_item['product_id'], $hidden_ids ) ) {
        return '<span>1</span>';
    }
    return $product_quantity;
}

add_action('pre_get_posts', 'hide_special_products_from_shop');
function hide_special_products_from_shop( $query ) {
    if ( ! is_admin() && $query->is_main_query() && ( is_shop() || is_product_category() || is_product_tag() || is_search() ) ) {
        $hidden_ids = [28505, 28508, 28804, 28806, 28807, 28808, 28809];
        $query->set( 'post__not_in', $hidden_ids );
    }
}

add_filter('woocommerce_cart_contents_count', 'exclude_special_products_from_free_shipping_count');
function exclude_special_products_from_free_shipping_count( $count ) {
    $hidden_ids = [28505, 28508, 28804, 28806, 28807, 28808, 28809];
    $adjusted = 0;
    foreach ( WC()->cart->get_cart() as $item ) {
        if ( ! in_array( $item['product_id'], $hidden_ids ) ) {
            $adjusted += $item['quantity'];
        }
    }
    return $adjusted;
}

add_action('wp_ajax_add_to_cart', 'add_to_cart_function');
add_action('wp_ajax_nopriv_add_to_cart', 'add_to_cart_function');
function add_to_cart_function() {
    $product_id = intval($_POST['product_id']);
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 1;
    if ( $product_id ) {
        $added = WC()->cart->add_to_cart( $product_id, $quantity );
        if ( $added ) {
            wp_send_json_success('Proizvod je uspešno dodat u korpu.');
        } else {
            wp_send_json_error('Greška: Proizvod nije mogao biti dodat u korpu.');
        }
    } else {
        wp_send_json_error('Greška: Proizvod nije pronađen.');
    }
    wp_die();
}