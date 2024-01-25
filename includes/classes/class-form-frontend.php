<?php
/**
 * bluee <<file header>>
 *
 * @package bluee
 */

namespace BFB;

class Form_Frontend
{
    private array $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;

        if (!empty($this->fields)) {
            echo '<form method="POST">';
            wp_nonce_field('bfb-frontend-form', 'nonce');
            echo '<div class="bfb-form-inner">';
            foreach ($this->fields as $field) {
                $this->render_type($field['type'], $field);
            }
            echo '</div>';
            echo '</form>';
            if (isset($_POST['nonce'])) {
                $this->save();
            }
        }
    }

    /**
     * Render field.
     *
     * @param string $type Field type.
     * @param array $options Field options.
     * @return void
     */
    private function render_type(string $type, array $options = [])
    {
        $templateDir = BFB_RESOURCES_DIR . sprintf('/views/fields/%s-tmpl.php', $type);
        printf('<div class="form-type form-type--%s">', $type);
        if (file_exists($templateDir)) {
            global $bfb_field_options;
            $bfb_field_options = $options;
            include $templateDir;
        } else {
            echo '<p>';
            printf(esc_html__('%s is undefined type.', 'bfb'), $type);
            echo '</p>';
        }
        echo '</div>';
    }

    /**
     * Render form frontend.
     *
     * @return void
     */
    public static function render()
    {
        $fields = get_option('bfb_form_data', []);
        if (empty($fields)) {
            return;
        }
        new self($fields);
    }

    private function render_message(string $type = 'success', string $message = '')
    {
        printf('<div class="bfb-message bfb-message--%s">', $type);
        echo esc_html($message);
        echo '</div>';
    }

    /**
     * Save form data.
     *
     * @return void
     */
    private function save()
    {
        if (empty($_POST['nonce']) || !wp_verify_nonce(wp_unslash(sanitize_text_field($_POST['nonce'])), 'bfb-frontend-form')) {
            $this->render_message('error', __('An authentication error occurred!', 'bfb'));
            return;
        }
        $fields = get_option('bfb_form_data', []);
        if (empty($fields)) {
            return;
        }
        $booking_details = [];
        foreach ($fields as $field) {
            $field_data = sanitize_text_field(wp_unslash($_POST[$field['name']] ?? ''));
            if (empty($field_data) && isset($field['required']) && $field['required']) {
                $this->render_message('error', $field['name'] . __('is required!', 'bfb'));
                return;
            }
            if (empty($field_data)) {
                continue;
            }
            $booking_details[] = array(
                'label' => $field['label'],
                'value' => $field_data
            );
        }

        $post_data = array(
            'post_title' => esc_html__('New booking', 'bfb') . ' ' . $booking_details[0]['value'],
            'post_type' => 'booking',
            'post_status' => 'publish',
            'meta_input' => array(
                'form_data' => $booking_details
            )
        );

        if (is_wp_error(wp_insert_post($post_data))) {
            $this->render_message('error', __('An error occurred!', 'bfb'));
            return;
        }

        $this->render_message('success', __('Your reservation has been made successfully.', 'bfb'));
    }
}