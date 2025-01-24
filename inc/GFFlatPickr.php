<?php

if (!class_exists('GFForms')) {
    return;
}

class GF_Field_Flatpickr extends GF_Field {

    public $type = 'flatpickr';

    public function get_form_editor_field_title() {
        return esc_attr__('Flatpickr Date Picker', 'gravityforms');
    }

    public function get_form_editor_button() {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function get_form_editor_field_settings() {
        return [
            'conditional_logic_field_setting',
            'error_message_setting',
            'label_setting',
            'description_setting',
            'css_class_setting',
            'flatpickr_date_format_setting', // Custom setting for date format
        ];
    }

    public function render_field_input($form, $value = '', $entry = null) {
        $id = esc_attr($this->id);
        $class = esc_attr($this->cssClass);
        $date_format = esc_attr($this->date_format ?? 'Y-m-d'); // Default format

        // Enqueue Flatpickr assets
        wp_enqueue_script('flatpickr', 'https://cdn.jsdelivr.net/npm/flatpickr', [], null, true);
        wp_enqueue_style('flatpickr-style', 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css', [], null);

        // Render the input
        return sprintf(
            '<input type="text" name="input_%s" id="input_%s" class="flatpickr %s" value="%s" data-format="%s" />',
            $id,
            $id,
            $class,
            esc_attr($value),
            $date_format
        );
    }

    public function add_inline_script() {
        echo "<script>
            document.addEventListener('DOMContentLoaded', function () {
                const inputs = document.querySelectorAll('.flatpickr');
                inputs.forEach(input => {
                    flatpickr(input, {
                        dateFormat: input.dataset.format
                    });
                });
            });
        </script>";
    }
}

// Register the field type
GF_Fields::register(new GF_Field_Flatpickr());








add_action('gform_field_standard_settings', function($position, $form_id) {
    if ($position == 25) {
        ?>
        <li class="flatpickr_date_format_setting field_setting">
            <label for="field_flatpickr_date_format">
                <?php esc_html_e('Date Format', 'gravityforms'); ?>
                <?php gform_tooltip('field_flatpickr_date_format'); ?>
            </label>
            <input type="text" id="field_flatpickr_date_format" oninput="SetFieldProperty('date_format', this.value);" />
        </li>
        <?php
    }
}, 10, 2);



add_filter('gform_tooltips', function($tooltips) {
    $tooltips['field_flatpickr_date_format'] = __('Set the date format for Flatpickr (e.g., Y-m-d, d/m/Y).', 'gravityforms');
    return $tooltips;
});
