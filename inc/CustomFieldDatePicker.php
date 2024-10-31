<?php
namespace BDGF;




class GF_Field_ModernDatePicker extends \GF_Field {

    public $type = 'modern_date_picker'; // Unique field type identifier

    // Store the custom properties
    public $min_date;
    public $max_date;

    public function get_form_editor_field_title() {
        return esc_attr__('Modern Date Picker', 'gravityforms');
    }

    public function get_form_editor_button() {
        return [
            'group' => 'advanced_fields',
            'text'  => $this->get_form_editor_field_title(),
        ];
    }

    public function get_form_editor_field_settings() {
        return [
            'label_setting',
            'description_setting',
            'css_class_setting',
            'visibility_setting',
            'conditional_logic_field_setting',
            'error_message_setting',
            'rules_setting', // Adds standard 'Required' checkbox
            'size_setting',
            'min_date_setting',
            'max_date_setting'
            ];
    }

    public function get_field_input($form, $value = '', $entry = null) {
        $form_id = $form['id'];
        $field_id = $this->id;
        $field_name = 'input_' . $field_id;

        // Get the size setting, default to 'medium' if not set
        $size = !empty($this->size) ? $this->size : 'medium';

        $required = $this->isRequired ? 'required' : '';
        $min = isset($this->min_date) ? 'min="' . esc_attr($this->min_date) . '"' : '';
        $max = isset($this->max_date) ? 'max="' . esc_attr($this->max_date) . '"' : '';
    
        // Include the size setting in the class attribute
        $input_class = trim("{$this->cssClass} {$size}");


        return sprintf(
            '<div class="ginput_container ginput_container_modern_date_picker"><input type="date" name="%s" id="input_%d_%d" value="%s" class="gfbd-modern-date-picker %s" %s %s %s /></div>',
            esc_attr($field_name),
            esc_attr($form_id),
            esc_attr($field_id),
            esc_attr($value),
            esc_attr($input_class),
            $required,
            $min,
            $max
        );
    }
    

    public function get_form_editor_inline_script_on_page_render() {
        return <<<JS
            (function($) {
                $(document).on('input', 'input[type="date"]', function() {
                    const min = $(this).attr('min');
                    const max = $(this).attr('max');
                    const value = $(this).val();
                    if (min && value < min) $(this).val(min);
                    if (max && value > max) $(this).val(max);
                });
            })(jQuery);
        JS;
    }



    public function sanitize_settings() {
        $this->minDate = sanitize_text_field(rgpost('min_date'));
        $this->maxDate = sanitize_text_field(rgpost('max_date'));
        return true;
    }
    

}




add_action('gform_loaded', function() {

    if (!class_exists('\GF_Field')) {
        return;
    }

    \GF_Fields::register(new \BDGF\GF_Field_ModernDatePicker());

});


add_action('gform_field_advanced_settings', function($position, $form_id) {
    // Inject our custom settings at a specific position (e.g., after position 25)
    if ($position == 25) {
        ?>
        <li class="min_date_setting field_setting">
            <label for="field_min_date">
                <?php esc_html_e('Minimum Date', 'gravityforms'); ?>
                <input type="text" id="field_min_date" class="fieldwidth-3" placeholder="YYYY-MM-DD or +X days"/>
            </label>
        </li>
        <li class="max_date_setting field_setting">
            <label for="field_max_date">
                <?php esc_html_e('Maximum Date', 'gravityforms'); ?>
                <input type="text" id="field_max_date" class="fieldwidth-3" placeholder="YYYY-MM-DD or +X days"/>
            </label>
        </li>
        <?php
    }
}, 10, 2);


add_action('gform_editor_js', function() {
    ?>
    <script type="text/javascript">
        jQuery(document).on('gform_load_field_settings', function(event, field) {
            if (field.type === 'modern_date_picker') {
                // Ensure saved values populate the min/max date fields
                jQuery('#field_min_date').val(field.min_date ? field.min_date : '');
                jQuery('#field_max_date').val(field.max_date ? field.max_date : '');
            }
        });

        jQuery(document).on('input', '#field_min_date, #field_max_date', function() {
            // Save the field settings as the user types
            var setting = jQuery(this).attr('id').replace('field_', '');
            SetFieldProperty(setting, jQuery(this).val());
        });
    </script>
    <?php
});


add_filter('gform_field_content', function($content, $field) {
    if ($field->type === 'modern_date_picker') {
        $field->min_date = isset($field->min_date) ? $field->min_date : '';
        $field->max_date = isset($field->max_date) ? $field->max_date : '';
    }
    return $content;
}, 10, 2);





add_action('wp_footer', function() {
    ?>
    <script type="text/javascript">
        /**
         * Gravity Forms hook that runs after the form is rendered.
         * Ensures date pickers respect both absolute and relative min/max dates.
         */
        jQuery(document).on('gform_post_render', function(event, form_id, current_page) {
            const dateInputs = document.querySelectorAll('.ginput_container_modern_date_picker input');

            dateInputs.forEach(input => {
                const minDateAttr = input.getAttribute('min');
                const maxDateAttr = input.getAttribute('max');

                const minDate = parseRelativeDate(minDateAttr);
                const maxDate = parseRelativeDate(maxDateAttr);

                // Apply min and max dates to the input field (as YYYY-MM-DD)
                if (minDate) input.setAttribute('min', formatDate(minDate));
                if (maxDate) input.setAttribute('max', formatDate(maxDate));

                // Enforce min/max restrictions on input
                input.addEventListener('input', function() {
                    const inputValue = new Date(this.value);
                    if (minDate && inputValue < minDate) this.value = formatDate(minDate);
                    if (maxDate && inputValue > maxDate) this.value = formatDate(maxDate);
                });

                // Revalidate on change to handle manual edits
                input.addEventListener('change', function() {
                    const inputValue = new Date(this.value);
                    if (minDate && inputValue < minDate) this.value = formatDate(minDate);
                    if (maxDate && inputValue > maxDate) this.value = formatDate(maxDate);
                });
            });

            return true; // Continue with the form rendering process

            /**
             * Parse relative dates like '+7 days' or '-3 days'.
             * Handles optional spaces and is case-insensitive.
             */
            function parseRelativeDate(dateStr) {
                if (!dateStr) return null;

                const relativePattern = /^([+-]?\d+)\s*days?$/i;
                const match = dateStr.trim().toLowerCase().match(relativePattern);

                if (match) {
                    const offset = parseInt(match[1], 10); // Get the numeric offset
                    const today = new Date();
                    today.setDate(today.getDate() + offset); // Apply the offset
                    return today;
                }

                // Try parsing it as an absolute date
                const absoluteDate = new Date(dateStr);
                return isNaN(absoluteDate) ? null : absoluteDate;
            }

            /**
             * Format a Date object as 'YYYY-MM-DD' for input[type="date"] fields.
             */
            function formatDate(date) {
                const year = date.getFullYear();
                const month = String(date.getMonth() + 1).padStart(2, '0');
                const day = String(date.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
        });
    </script>
    <?php
});
