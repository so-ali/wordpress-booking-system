<?php
/**
 * Plugin bootstrap class.
 */

namespace BFB;

final class Bootstrap
{
    /**
     * Bootstrap Class constructor.
     */
    public function __construct()
    {
        add_action('plugins_loaded', [$this, 'plugin_loaded']);
        add_shortcode('bfb_form_shortcode', [$this, 'shortcode']);
    }

    /**
     * The plugin loader.
     *
     * @return void
     */
    public function plugin_loaded()
    {
        if ($this->is_compatible()) {
            $this->initialize();
        } else {
            add_action('admin_notices', [$this, 'compatible_notice']);
        }
    }

    public function compatible_notice()
    {
        echo '<div class="notice notice-error is-dismissible"><p>';
        esc_html_e('The Booking Form Builder plugin requires at least WordPress 5.2 & PHP 7.4', 'bfb');
        echo '</p></div>';
    }

    /**
     * Check the plugin loading conditions.
     *
     * @return bool
     */
    public function is_compatible(): bool
    {
        global $wp_version;
        return version_compare(phpversion(), '7.4', '>=') && version_compare($wp_version, '5.2', '>=');
    }

    /**
     * Initialize the plugin parts.
     *
     * @return void
     */
    public function initialize()
    {
        $this->require_parts();
        new Form_Builder();
    }

    /**
     * Require the plugin parts.
     *
     * @return void
     */
    public function require_parts()
    {
        require_once realpath(__DIR__ . '/hooks/post-types.php');
        require_once realpath(__DIR__ . '/classes/class-form-builder.php');
        require_once realpath(__DIR__ . '/classes/class-form-frontend.php');
    }

    public static function shortcode()
    {
        wp_enqueue_script( 'jquery' );
        wp_enqueue_script( 'jquery-ui-datepicker' );
        wp_enqueue_script( 'bfb-frontend',BFB_RESOURCES_URL . '/scripts/frontend.js', ['jquery'], BFB_VERSION );
        wp_enqueue_style( 'bfb-frontend',BFB_RESOURCES_URL . '/styles/frontend.css', false, BFB_VERSION );
        ob_start();
        Form_Frontend::render();
        return ob_get_clean();
    }

    /**
     * Instance of the plugin bootstrap.
     *
     * @return self
     */
    public static function instance(): self
    {
        global $bfb_instance;
        if (empty($bfb_instance)) {
            $bfb_instance = new self();
        }

        return $bfb_instance;
    }
}

\BFB\Bootstrap::instance();