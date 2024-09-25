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
        return 'SquareIcon';
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
        return get_class();
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
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => ['0' => ['value' => 'vertical', 'text' => 'Vertical'], '1' => ['text' => 'Horizontal', 'value' => 'horizontal']]],
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
        ['type' => 'repeater', 'layout' => 'vertical', 'condition' => ['0' => ['0' => ['path' => 'design.form_elements.labels.hide_labels', 'operand' => 'is set', 'value' => '']]], 'repeaterOptions' => ['titleTemplate' => '{selector}', 'defaultTitle' => 'Selector', 'buttonName' => 'Add Selector']],
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
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => ['0' => ['value' => 'vertical', 'text' => 'Vertical'], '1' => ['text' => 'Horizontal', 'value' => 'horizontal']]],
        true,
        false,
        [],
      ), c(
        "gap",
        "Gap",
        [],
        ['type' => 'unit', 'layout' => 'inline', 'rangeOptions' => ['step' => 1], 'unitOptions' => ['types' => ['0' => 'rem', '1' => 'em', '2' => 'px', '3' => '%']]],
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
        ['type' => 'dropdown', 'layout' => 'inline', 'items' => ['0' => ['value' => 'standard', 'text' => 'Standard'], '1' => ['text' => 'Blocks', 'value' => 'blocks']]],
        false,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\simpleLayout",
      "Layout",
      "layout",
       ['condition' => ['0' => ['0' => ['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\typography",
      "Typography",
      "typography",
       ['condition' => ['0' => ['0' => ['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
     ), getPresetSection(
      "EssentialElements\\borders",
      "Borders",
      "borders",
       ['condition' => ['0' => ['0' => ['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]], 'type' => 'popout']
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
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout'], 'condition' => ['0' => ['0' => ['path' => 'design.form_elements.radio_checkbox.radio_type', 'operand' => 'equals', 'value' => 'blocks']]]],
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
        ['type' => 'button_bar', 'layout' => 'inline', 'items' => ['0' => ['value' => 'left', 'text' => 'Align Left', 'icon' => 'FlexAlignLeftIcon'], '1' => ['text' => 'Align Center', 'value' => 'center', 'icon' => 'FlexAlignCenterHorizontalIcon'], '2' => ['text' => 'Align Right', 'value' => 'right', 'icon' => 'FlexAlignRightIcon']], 'buttonBarOptions' => ['size' => 'small', 'layout' => 'default']],
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
     )],
        ['type' => 'section', 'sectionOptions' => ['type' => 'popout']],
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
}'],],];
    }

    static function settings()
    {
        return false;
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
        return ['0' => ['cssProperty' => 'margin-top', 'location' => 'outside-top', 'affectedPropertyPath' => 'design.spacing.%%BREAKPOINT%%.margin_top'], '1' => ['cssProperty' => 'margin-bottom', 'location' => 'outside-bottom', 'affectedPropertyPath' => 'design.spacing.%%BREAKPOINT%%.margin_bottom']];
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
        return ['0' => ['accepts' => 'image_url', 'path' => 'design.form_elements.validation.background.layers[].image'], '1' => ['accepts' => 'image_url', 'path' => 'design.form_elements.radio_checkbox.active.background.layers[].image'], '2' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '3' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '4' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '5' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '6' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '7' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '8' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '9' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string'], '10' => ['path' => 'settings.advanced.attributes[].value', 'accepts' => 'string']];
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
        return ['design.form_elements.footer.layout.horizontal.vertical_at', 'design.form_elements.vertical_at', 'design.form_elements.footer.styles.styles.size.full_width_at', 'design.form_elements.footer.button.custom.size.full_width_at', 'design.form_elements.footer.button.styles', 'design.form_elements.radio_checkbox.layout.horizontal.vertical_at', 'design.form_elements.radio_checkbox.de_select_all_button.custom.size.full_width_at', 'design.form_elements.radio_checkbox.de_select_all_button.styles', 'design.form_elements.list.remove_button.custom.size.full_width_at', 'design.form_elements.list.remove_button.styles', 'design.footer.submit_button.custom.size.full_width_at', 'design.footer.submit_button.styles', 'design.footer.previous_button.custom.size.full_width_at', 'design.footer.previous_button.styles', 'design.footer.next_button.custom.size.full_width_at', 'design.footer.next_button.styles'];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return [''];
    }
}
