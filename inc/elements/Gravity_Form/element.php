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
      "Layout",
      "layout",
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
        "footer",
        "Footer",
        [c(
        "submit_alignment",
        "Submit Alignment",
        [],
        ['type' => 'button_bar', 'layout' => 'inline', 'items' => [['value' => 'left', 'text' => 'Align Left', 'icon' => 'FlexAlignLeftIcon'], ['text' => 'Align Center', 'value' => 'center', 'icon' => 'FlexAlignCenterHorizontalIcon'], ['text' => 'Align Right', 'value' => 'right', 'icon' => 'FlexAlignRightIcon']], 'buttonBarOptions' => ['size' => 'small', 'layout' => 'default']],
        true,
        false,
        [],
      ), getPresetSection(
      "EssentialElements\\AtomV1ButtonDesign",
      "Button",
      "button",
       ['type' => 'popout']
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
        return ['0' =>  ['styles' => ['%%BREAKDANCE_REUSABLE_BDGF_DEFAULT_CSS%%'],'title' => 'Default CSS',],'1' =>  ['title' => 'Breakdance Form CSS','styles' => ['%%BREAKDANCE_ELEMENTS_PLUGIN_URL%%dependencies-files/awesome-form@1/css/form.css'],],];
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
        return ['design.form_elements.footer.layout.horizontal.vertical_at', 'design.form_elements.vertical_at', 'design.form_elements.footer.styles.styles.size.full_width_at', 'design.form_elements.footer.button.custom.size.full_width_at', 'design.form_elements.footer.button.styles', 'design.form_elements.radio_checkbox.layout.horizontal.vertical_at', 'design.form_elements.radio_checkbox.de_select_all_button.custom.size.full_width_at', 'design.form_elements.radio_checkbox.de_select_all_button.styles', 'design.form_elements.list.remove_button.custom.size.full_width_at', 'design.form_elements.list.remove_button.styles'];
    }

    static function propertyPathsToSsrElementWhenValueChanges()
    {
        return false;
    }
}
