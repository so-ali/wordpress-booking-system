<?php
/**
 * Button template.
 */
global $bfb_field_options;
$options = wp_parse_args($bfb_field_options, array(
    'label' => __('Text Input', 'bfb'),
    'name' => 'text-input',
    'value' => ''
));

?>
<button name="<?php echo esc_attr($options['name']); ?>" class="<?php echo esc_attr($options['className']); ?>"><?php echo esc_attr($options['label']); ?></button>

