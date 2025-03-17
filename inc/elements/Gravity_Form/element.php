<?php

namespace BDGF;

use function Breakdance\Elements\c;
use function Breakdance\Elements\PresetSections\getPresetSection;


\Breakdance\ElementStudio\registerElementForEditing(
    "BDGF\\GravityForm",
    \Breakdance\Util\getdirectoryPathRelativeToPluginFolder(__DIR__)
);

class GravityForm extends \Breakdance\Elements\Element
{
    static function uiIcon()
    {
        return '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24.77 27">   <path d="M24.09,8.87c-.08-1.29-.78-2.46-1.88-3.15L14.16,1.21c-1.17-.57-2.55-.56-3.72.02L2.45,5.85c-1.09.7-1.77,1.89-1.84,3.18l.07,9.11c.08,1.29.78,2.47,1.88,3.16l8.05,4.49c1.17.57,2.55.57,3.72-.02l7.99-4.6c1.09-.71,1.77-1.89,1.83-3.18h0s-.06-9.11-.06-9.11ZM20.7,11.39h-10.57c-.57-.03-1.13.18-1.53.59-.84.89-1.29,2.61-1.36,3.57h10.4v-2.58h2.97v5.54H4.07s.06-6.12,2.36-8.55c.97-1.01,2.32-1.56,3.72-1.52h10.54v2.95Z" fill="#f15a2b" stroke-width="0"/> </svg>';
    }

    static function tag()
    {
        return 'div';
    }

    static function tagOptions()
    {
        return [];
    }

    static function tagControlPath()
    {
        return false;
    }

    static function name()
    {
        return 'Gravity Form';
    }

    static function className()
    {
        return 'bdgf';
    }

    static function category()
    {
        return 'forms';
    }

    static function badge()
    {
        return false;
    }

    static function slug()
    {
        return __CLASS__;
    }

    static function template()
    {
        return file_get_contents(__DIR__ . '/html.twig');
    }

    static function defaultCss()
    {
        return file_get_contents(__DIR__ . '/default.css');
    }

    static function defaultProperties()
    {
        return false;
    }

    static function defaultChildren()
    {
        return false;
    }

    static function cssTemplate()
    {
        $template = file_get_contents(__DIR__ . '/css.twig');
        return $template;
    }

    static function designControls()
    {
        return [c(
        "form_elements",
        "Form Elements",
        [c(
        "direction",
        "Direction",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['value' => 'vertical', 'text' => 'Vertical'], ['text' => 'Horizontal', 'value' => 'horizontal']]],
        false,
        false,
        [],
      ), c(
        "vertical_at",
        "Vertical At",
        [],
        ['type' => 'breakpoint_dropdown', 'layout' => 'inline', 'breakpointOptions' => ['multiple' => false, 'enableNever' => true]],
        false,
        false,
        [],
      ), c(
        "labels",
        "Labels",
        [c(
        "label_note",
        "Label Note",
        [],
        ['type' => 'alert_box', 'layout' => 'vertical', 'alertBoxOptions' => ['style' => 'info', 'content' => '<p>We won\'t ever hide labels on radios, checkboxes or dropdowns.</p>']],
        false,
        false,
        [],
      ), c(
        "hide_labels",
        "Hide Labels",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "hide_complex_labels",
        "Hide Complex Labels",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "exclusions",
        "Exclusions",
        [c(
        "selector",
        "Selector",
        [],
        ['type' => 'text', 'layout' => 'vertical', 'textOptions' => ['format' => 'plain']],
        false,
        false,
        [],
      )],
        ['type' => 'repeater', 'layout' => 'vertical', 'condition' => [[['path' => 'design.form_elements.labels.hide_labels', 'operand' => 'is set', 'value' => '']]], 'repeaterOptions' => ['titleTemplate' => '{selector}', 'defaultTitle' => 'Selector', 'buttonName' => 'Add Selector']],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Primary Spacing",
      "primary_spacing",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Description Spacing",
      "description_spacing",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Sub-Label Spacing",
      "sub_label_spacing",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Primary Typography",
      "primary_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Description Typography",
      "description_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Sub-Label Typography",
      "sub_label_typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "radio_checkbox",
        "Radio & Checkbox",
        [c(
        "layout",
        "Layout",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['value' => 'vertical', 'text' => 'Vertical'], ['text' => 'Horizontal', 'value' => 'horizontal']]],
        true,
        false,
        [],
      ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['step' => 1], 'unitOptions' => ['types' => ['rem', 'em', 'px', '%']]],
        true,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Choices Spacing",
      "choices_spacing",
       ['type' => 'popout']
     ), c(
        "radio_type",
        "Radio Type",
        [],
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => [['value' => 'standard', 'text' => 'Standard'], ['text' => 'Blocks', 'value' => 'blocks']]],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\simpleLayout",
      "Choices Layout",
      "choices_layout",
       ['condition' => [[['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['condition' => [[['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['condition' => [[['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
     ), c(
        "active",
        "Active",
        [c(
        "background_color",
        "Background Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout'], 'condition' => [[['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]]],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "De/Select All Button",
      "de_select_all_button",
       ['type' => 'popout']
     ), c(
        "description",
        "Description",
        [getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Spacing",
      "spacing",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "inputs",
        "Inputs",
        [c(
        "width_default",
        "Width Default",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "width_large",
        "Width Large",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "width_medium",
        "Width Medium",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "width_small",
        "Width Small",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "multiselect",
        "Multiselect",
        [getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Choices Spacing",
      "choices_spacing",
       ['type' => 'popout']
     ), c(
        "description",
        "Description",
        [getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Spacing",
      "spacing",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "list",
        "List",
        [c(
        "hide_button_label",
        "Hide Button Label",
        [],
        ['type' => 'toggle', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Add Button",
      "add_button",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Remove Button",
      "remove_button",
       ['type' => 'popout']
     ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "margin_bottom",
        "Margin Bottom",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "time",
        "Time",
        [c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['step' => 1], 'unitOptions' => ['types' => ['rem', 'em', 'px', '%']]],
        true,
        false,
        [],
      )],
        ['type' => 'section', 'layout' => 'inline', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "product",
        "Product",
        [c(
        "calculation",
        "Calculation",
        [getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Price Spacing ",
      "price_spacing_",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Price Typography",
      "price_typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "validation",
        "Validation",
        [c(
        "container",
        "Container",
        [c(
        "background_color",
        "Background Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_padding_all",
      "Padding",
      "padding",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "messages",
        "Messages",
        [getPresetSection(
      "EssentialElements\\typography",
      "Messages",
      "messages",
       ['type' => 'popout']
     ), c(
        "background_color",
        "Background Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_padding_all",
      "Padding",
      "padding",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Margin",
      "margin",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\typography",
      "H2 Messages",
      "h2_messages",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "List Items",
      "list_items",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "confirmation",
        "Confirmation",
        [getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Field Spacing",
      "field_spacing",
       ['type' => 'popout']
     ), c(
        "uploader",
        "Uploader",
        [getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Trash Button",
      "trash_button",
       ['type' => 'popout']
     ), c(
        "icon",
        "Icon",
        [],
        ['type' => 'icon', 'layout' => 'vertical'],
        false,
        false,
        [],
      ), c(
        "color",
        "Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "size",
        "Size",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "progress_bar",
        "Progress Bar",
        [c(
        "background_color",
        "Background Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "progress_color",
        "Progress Color",
        [],
        ['type' => 'color', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     ), c(
        "height",
        "Height",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_x",
      "Text Margin",
      "text_margin",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Margin",
      "margin",
       ['type' => 'popout']
     )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "steps",
        "Steps",
        [getPresetSection(
      "EssentialElements\\typography",
      "Title Typography",
      "title_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Steps Typography",
      "steps_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Current Step Typography",
      "current_step_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Margin",
      "margin",
       ['type' => 'popout']
     )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "numbered_steps",
        "Numbered Steps",
        [c(
        "size",
        "Size",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\typography",
      "Number Typography",
      "number_typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Label Typography",
      "label_typography",
       ['type' => 'popout']
     ), c(
        "label_margin_left",
        "Label Margin Left",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "text",
        "Text",
        [c(
        "section_title",
        "Section Title",
        [getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Margin",
      "margin",
       ['type' => 'popout']
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\borders",
      "Section Borders",
      "section_borders",
       ['type' => 'popout']
     ), c(
        "html_field",
        "HTML Field",
        [c(
        "info",
        "Info",
        [],
        ['type' => 'alert_box', 'layout' => 'vertical', 'alertBoxOptions' => ['style' => 'default', 'content' => '<p>Provide simple selectors. They will only target this form.</p>']],
        false,
        false,
        [],
      ), c(
        "selectors",
        "Selectors",
        [],
        ['type' => 'text', 'layout' => 'vertical'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\typography_with_align",
      "Typography",
      "typography",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Margin",
      "margin",
       ['type' => 'popout']
     )],
        ['type' => 'repeater', 'layout' => 'vertical', 'repeaterOptions' => ['titleTemplate' => '{tag}', 'defaultTitle' => 'Tag Style', 'buttonName' => '']],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "footer",
        "Footer",
        [c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "button_alignment",
        "Button Alignment",
        [],
        ['type' => 'button_bar', 'layout' => 'inline', 'items' => [['value' => 'left', 'text' => 'Align Left', 'icon' => 'FlexAlignLeftIcon'], ['text' => 'Align Center', 'value' => 'center', 'icon' => 'FlexAlignCenterHorizontalIcon'], ['text' => 'Align Right', 'value' => 'right', 'icon' => 'FlexAlignRightIcon']], 'buttonBarOptions' => ['size' => 'small', 'layout' => 'default']],
        true,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Submit Button",
      "submit_button",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Previous Button",
      "previous_button",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Next Button",
      "next_button",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Save & Continue Button",
      "save_continue_button",
       ['type' => 'popout']
     ), c(
        "button_order",
        "Button Order",
        [],
        ['type' => 'multiselect', 'layout' => 'inline', 'items' => [['value' => 'gform_previous_button', 'text' => 'Previous'], ['text' => 'Next', 'value' => 'gform_next_button'], ['text' => 'Save & Continue', 'value' => 'gform_save_link'], ['text' => 'Submit', 'value' => 'gform_submit']]],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
        false,
        false,
        [],
      ), c(
        "gravity_variables",
        "Gravity Variables",
        [c(
        "padding_y",
        "Padding Y",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "padding_block",
        "Padding Block",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      ), c(
        "font_size_primary",
        "Font Size Primary",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        false,
        false,
        [],
      )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), c(
        "container",
        "Container",
        [c(
        "max_width",
        "Max Width",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        true,
        false,
        [],
      ), c(
        "min_height",
        "Min Height",
        [],
        ['type' => 'unit', 'layout' => 'inline'],
        true,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_padding_all",
      "Padding",
      "padding",
       ['type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['type' => 'popout']
     )],
        ['type' => 'section'],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\spacing_margin_y",
      "Spacing",
      "spacing",
       ['type' => 'popout']
     )];
    }

    static function contentControls()
    {
        return [c(
        "controls",
        "Controls",
        [c(
        "form",
        "Form",
        [],
        ['type' => 'dropdown', 'layout' => 'vertical', 'dropdownOptions' => ['populate' => ['path' => '', 'text' => '', 'value' => '', 'fetchDataAction' => 'bdgf_get_forms', 'fetchContextPath' => 'content.controls.form', 'refetchPaths' => []]]],
        false,
        false,
        [],
      ), c(
        "show_title",
        "Show Title",
        [],
        ['type' => 'toggle', 'layout' => 'vertical'],
        false,
        false,
        [],
      ), c(
        "show_description",
        "Show Description",
        [],
        ['type' => 'toggle', 'layout' => 'vertical'],
        false,
        false,
        [],
      ), c(
        "notes",
        "Notes",
        [],
        ['type' => 'alert_box', 'layout' => 'vertical', 'alertBoxOptions' => ['style' => 'info', 'content' => '<p>Forms that contain conditional logic will display all fields in Breakdance layout. Conditional logic will be preserved on the front end.</p>']],
        false,
        false,
        [],
      ), c(
        "process_with_ajax",
        "Process with AJAX",
        [],
        ['type' => 'toggle', 'layout' => 'vertical'],
        false,
        false,
        [],
      )],
        ['type' => 'section', 'layout' => 'vertical'],
        false,
        false,
        [],
      )];
    }

    static function settingsControls()
    {
        return [];
    }

    static function dependencies()
    {
        return ['0' =>  ['styles' => ['%%BREAKDANCE_REUSABLE_BDGF_DEFAULT_CSS%%'],'title' => 'Default CSS',],'1' =>  ['title' => 'Breakdance Form CSS','styles' => ['%%BREAKDANCE_ELEMENTS_PLUGIN_URL%%dependencies-files/awesome-form@1/css/form.css'],],'2' =>  ['frontendCondition' => 'return false;','builderCondition' => 'return true;','inlineStyles' => ['.bdgf .gform_page {
  display:block !important;
  position:relative;
}

.bdgf .gform_page:after {
  content:"This is a page divider in the builder only";
  height: 150px;
  background-color: var(--grey-400);
  width: 100%;
  position:relative;
  display:flex;
  justify-content:center;
  align-items:center;
  margin-top:2rem;
  margin-bottom:2rem;
}'],'title' => 'Multipage forms - builder',],'3' =>  ['title' => 'Save & Continue','inlineScripts' => ['function observeElement(targetSelector, classesToAdd) {
    const observer = new MutationObserver((mutationsList) => {
        mutationsList.forEach((mutation) => {
            if (mutation.type === \'childList\') {
                const element = document.querySelector(targetSelector);
                if (element) {
                    element.classList.add(...classesToAdd);
                    // Optionally stop observing once the element is found and classes are added
                    observer.disconnect();
                }
            }
        });
    });

    // Start observing the document body for changes
    observer.observe(document.body, { childList: true, subtree: true });
}

// Example: Add multiple observers for different elements
observeElement( \'input[name="gform_send_resume_link_button"\', [\'breakdance-form-button\', \'button-atom\', \'button-atom--primary\']);
observeElement(\'#gform_resume_email\', [\'breakdance-form-field__input\']);
observeElement(\'.form_saved_message form\', [\'breakdance-form\']);'],'frontendCondition' => 'return true;','builderCondition' => 'return false;',],];
    }

    static function settings()
    {
        return ['bypassPointerEvents' => false, 'shareStateWithChildSSR' => false, 'dependsOnGlobalScripts' => true];
    }

    static function addPanelRules()
    {
        return false;
    }

    static public function actions()
    {
        return false;
    }

    static function nestingRule()
    {
        return ["type" => "final",   ];
    }

    static function spacingBars()
    {
        return [['cssProperty' => 'margin-top', 'location' => 'outside-top', 'affectedPropertyPath' => 'design.spacing.%%BREAKPOINT%%.margin_top'], ['cssProperty' => 'margin-bottom', 'location' => 'outside-bottom', 'affectedPropertyPath' => 'design.spacing.%%BREAKPOINT%%.margin_bottom']];
    }

    static function attributes()
    {
        return false;
    }

    static function experimental()
    {
        return false;
    }

    static function order()
    {
        return 0;
    }

    static function dynamicPropertyPaths()
    {
        return [['accepts' => 'image_url', 'path' => 'design.form_elements.validation.background.layers[].image'], ['accepts' => 'image_url', 'path' => 'design.form_elements.radio_checkbox.active.background.layers[].image']];
    }

    static function additionalClasses()
    {
        return false;
    }

    static function projectManagement()
    {
        return false;
    }

    static function propertyPathsToWhitelistInFlatProps()
    {
        return ['design.form_elements.footer.layout.horizontal.vertical_at', 'design.form_elements.vertical_at', 'design.form_elements.footer.styles.styles.size.full_width_at', 'design.form_elements.footer.button.custom.size.full_width_at', 'design.form_elements.footer.button.styles', 'design.form_elements.radio_checkbox.de_select_all_button.custom.size.full_width_at', 'design.form_elements.radio_checkbox.de_select_all_button.styles', 'design.form_elements.list.remove_button.custom.size.full_width_at', 'design.form_elements.list.remove_button.styles', 'design.footer.submit_button.custom.size.full_width_at', 'design.footer.submit_button.styles', 'design.footer.previous_button.custom.size.full_width_at', 'design.footer.previous_button.styles', 'design.footer.next_button.custom.size.full_width_at', 'design.footer.next_button.styles', 'design.footer.save_continue_button.custom.size.full_width_at', 'design.footer.save_continue_button.styles', 'design.form_elements.radio_checkbox.choices_layout.horizontal.vertical_at', 'design.form_elements.uploader.trash_button.custom.size.full_width_at', 'design.form_elements.uploader.trash_button.styles'];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
