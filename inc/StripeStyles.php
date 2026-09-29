<?php
namespace BDGF;

/** Stripe's public Appearance/Style APIs, resolved by the GF Stripe add-on. */
class StripeStyles {

    public static function get( $payment_element ) {
        if ( !$payment_element ) {
            // The legacy Card Element uses the Style API, not Appearance.
            return [
                'base' => [
                    'color' => '--bdgf-stripe-input-color',
                    'fontFamily' => '--bdgf-stripe-input-font-family',
                    'fontSize' => '--bdgf-stripe-input-font-size',
                    '::placeholder' => ['color' => '--bde-form-input-placeholder-color'],
                ],
                'invalid' => ['color' => '--bdgf-stripe-danger'],
            ];
        }

        $input = [
            'backgroundColor' => '--bde-form-input-background-color',
            'borderColor' => '--bde-form-input-border-color',
            'borderWidth' => '--bde-form-input-border-width',
            'borderStyle' => 'solid',
            'borderRadius' => '--bde-form-input-border-radius',
            'boxShadow' => 'none',
            'paddingTop' => '--bde-form-input-padding-top',
            'paddingRight' => '--bde-form-input-padding-right',
            'paddingBottom' => '--bde-form-input-padding-bottom',
            'paddingLeft' => '--bde-form-input-padding-left',
            'fontSize' => '--bdgf-stripe-input-font-size',
            'fontWeight' => '--bdgf-stripe-input-font-weight',
            'lineHeight' => '--bdgf-stripe-input-line-height',
            'letterSpacing' => '--bdgf-stripe-input-letter-spacing',
            'color' => '--bdgf-stripe-input-color',
        ];
        $focus = [
            'backgroundColor' => '--bde-form-input-focused-background-color',
            'borderColor' => '--bde-form-input-focused-border-color',
            'boxShadow' => '--bde-form-input-focused-shadow',
        ];

        return [
            // "minimal" is not a Stripe theme. Flat is a supported starting point.
            'theme' => 'flat',
            'variables' => [
                'fontFamily' => '--bdgf-stripe-input-font-family',
                'fontSizeBase' => '--bdgf-stripe-input-font-size',
                'colorPrimary' => '--bde-brand-primary-color',
                'colorBackground' => '--bde-form-input-background-color',
                'colorText' => '--bdgf-stripe-input-color',
                'colorTextSecondary' => '--bdgf-stripe-label-color',
                'colorTextPlaceholder' => '--bde-form-input-placeholder-color',
                'colorDanger' => '--bdgf-stripe-danger',
                'borderRadius' => '--bde-form-input-border-radius',
                'spacingGridRow' => '--bdgf-stripe-row-gap',
                'spacingGridColumn' => '--bde-form-gap',
            ],
            'rules' => [
                '.Input' => $input,
                '.Input:focus' => $focus,
                '.Input:hover:focus' => $focus,
                '.Input--invalid' => ['borderColor' => '--bdgf-stripe-danger'],
                '.Input::placeholder' => ['color' => '--bde-form-input-placeholder-color'],
                '.Label' => [
                    'color' => '--bdgf-stripe-label-color',
                    'fontFamily' => '--bdgf-stripe-label-font-family',
                    'fontSize' => '--bdgf-stripe-label-font-size',
                    'fontWeight' => '--bdgf-stripe-label-font-weight',
                    'lineHeight' => '--bdgf-stripe-label-line-height',
                    'letterSpacing' => '--bdgf-stripe-label-letter-spacing',
                    'marginBottom' => '--bdgf-stripe-label-margin-bottom',
                ],
                '.Tab' => [
                    'backgroundColor' => '--bde-form-input-background-color',
                    'borderColor' => '--bde-form-input-border-color',
                    'borderWidth' => '--bde-form-input-border-width',
                    'borderStyle' => 'solid',
                    'boxShadow' => 'none',
                ],
                '.Tab--selected' => ['borderColor' => '--bde-brand-primary-color'],
                '.Error' => ['color' => '--bdgf-stripe-danger'],
            ],
        ];
    }
}
