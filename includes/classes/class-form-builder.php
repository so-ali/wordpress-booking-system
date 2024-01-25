<?php
/**
 * Form Builder class file
 */

namespace BFB;

use mysql_xdevapi\Exception;

class Form_Builder
{
    /**
     * Autoload method
     * @return void
     */
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue'));
        add_action('admin_menu', array($this, 'register_sub_menu'));
        add_action('wp_ajax_bfb_form_save', array($this, 'save'));
    }

    /**
     * Register submenu
     * @return void
     */
    public function register_sub_menu()
    {
        add_submenu_page(
            'edit.php?post_type=booking', esc_html__('Form Builder', 'bfb'), esc_html__('Form Builder', 'bfb'), 'manage_options', 'form_builder', array($this, 'content')
        );
    }

    /**
     * Enqueue page scripts
     *
     * @return void
     */
    public function enqueue()
    {
        $screen = get_current_screen();
        if ($screen->id !== 'booking_page_form_builder') {
            return;
        }
        wp_enqueue_script('bfb-jquery-ui', BFB_RESOURCES_URL . '/scripts/jquery-ui.min.js', ['jquery'], BFB_VERSION);
        wp_enqueue_script('bfb-form-builder', BFB_RESOURCES_URL . '/scripts/form-builder.min.js', ['jquery', 'bfb-jquery-ui'], BFB_VERSION);
        wp_enqueue_script('bfb-admin', BFB_RESOURCES_URL . '/scripts/admin.js', ['jquery', 'bfb-form-builder'], BFB_VERSION);
        wp_localize_script('bfb-admin', 'BFB_OBJECT', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('bfb-form'),
            'form_data' => get_option('bfb_form_data', [])
        ]);


        wp_enqueue_style('bfb-form-builder', BFB_RESOURCES_URL . '/styles/bootstrap.min.css', false, BFB_VERSION);
        wp_enqueue_style('bfb-admin', BFB_RESOURCES_URL . '/styles/admin.css', false, BFB_VERSION);
    }

    /**
     * Render submenu
     *
     * @return void
     */
    public function content()
    {
        echo '<div class="wrap">';
        echo '<div class="notice notice-success is-dismissible"><p>';
        esc_html_e('The form shortcode is:', 'bfb');
        echo '</p>';
        echo '<p><input type="text" readonly value="[bfb_form_shortcode]"></p>';
        echo '</div>';
        echo '<div id="bfb-form-builder"></div>';
        echo '</div>';
    }


    public function save()
    {
        if (empty($_POST['nonce']) || wp_verify_nonce(wp_unslash(sanitize_text_field($_POST['nonce'])))) {
            wp_send_json_error([
                'message' => esc_html__('An authentication error occurred!', 'bfb')
            ]);
            return;
        }

        if (empty($_POST['data'])) {
            wp_send_json_error([
                'message' => esc_html__('The submitted form is wrong!', 'bfb')
            ]);
            return;
        }

        try {
            $data = json_decode(wp_unslash(sanitize_text_field($_POST['data'])), true);
        } catch (Exception $e) {
            wp_send_json_error([
                'message' => esc_html__('The submitted form is wrong!', 'bfb')
            ]);
            return;
        }

        update_option('bfb_form_data', $data, false);

        wp_send_json_success([
            'message' => 'The form was saved successfully.'
        ]);
    }
}