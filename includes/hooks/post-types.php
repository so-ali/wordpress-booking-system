<?php
/**
 * Functions which related to post types.
 */

/**
 * Register a custom post type called "booking".
 */
function bfb_register_booking_post_type()
{
    $labels = array(
        'name' => _x('Bookings', 'Post type general name', 'bfb'),
        'singular_name' => _x('Booking', 'Post type singular name', 'bfb'),
        'menu_name' => _x('Bookings', 'Admin Menu text', 'bfb'),
        'name_admin_bar' => _x('Booking', 'Add New on Toolbar', 'bfb'),
        'add_new' => __('Add New', 'bfb'),
        'add_new_item' => __('Add New Booking', 'bfb'),
        'new_item' => __('New Booking', 'bfb'),
        'edit_item' => __('Edit Booking', 'bfb'),
        'view_item' => __('View Booking', 'bfb'),
        'all_items' => __('All Bookings', 'bfb'),
    );

    $args = array(
        'labels' => $labels,
        'public' => false,
        'publicly_queryable' => false,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => false,
        'rewrite' => false,
        'capability_type' => 'post',
        'has_archive' => false,
        'hierarchical' => false,
        'menu_position' => null,
        'supports' => array('title'),
        'capabilities' => array(
            'create_posts' => false,
        ),
        'map_meta_cap' => true,
    );

    register_post_type('bhub-booking', $args);
}

/**
 * Display booking information.
 *
 * @return void
 */
function bfb_register_booking_metabox()
{
    add_meta_box('booking_information', esc_html__('Information', 'bfb'), 'bfb_view_booking_metabox', 'bhub-booking', 'normal');
}

/**
 * Booking information meta box content.
 *
 * @return void
 */
function bfb_view_booking_metabox()
{
    global $post;
    $booking_meta = get_post_meta($post->ID, 'form_data', true);
    if (empty($booking_meta) || !is_array($booking_meta)) {
        return;
    }

    foreach ($booking_meta as $item) {
        echo '<p>';
        printf('<b>%s:</b>', $item['label'] ?? '');
        printf('<span>%s</span>', $item['value'] ?? '');
        echo '</p>';
    }
}

add_action('init', 'bfb_register_booking_post_type');
add_action('admin_init', 'bfb_register_booking_metabox');