<?php
/**
 * Date picker input template.
 */
global $bfb_field_options;
$options = wp_parse_args($bfb_field_options, array(
    'label' => __('Text Input', 'bfb'),
    'name' => 'text-input',
    'value' => ''
));
?>
<label for="<?php echo esc_attr($options['name']); ?>">
    <?php echo esc_attr($options['label']); ?>
</label>
<input type="text" class="bfb-date-picker <?php echo esc_attr($options['className']); ?>" name="<?php echo esc_attr($options['name']); ?>" id="<?php echo esc_attr($options['name']); ?>"
       value="<?php echo esc_attr($options['value']); ?>">

