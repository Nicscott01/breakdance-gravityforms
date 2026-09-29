<?php
/** Integration check: wp eval-file path/to/tests/stripe-twig.php (Breakdance active). */
$template = file_get_contents( __DIR__ . '/../inc/elements/Gravity_Form/css.twig' );
$twig = \Breakdance\Render\Twig::getInstance();
$checks = 0;
$check = function( $condition, $message ) use ( &$checks ) {
    $checks++;
    if ( !$condition ) throw new RuntimeException( $message );
};
$empty = $twig->runTwig( $template, [] );
$check( !preg_match( '/--bdgf-stripe-[\w-]+:\s*;/', $empty ), 'Empty aliases override inherited defaults' );

$properties = [
    'globalSettings' => ['forms' => ['typography' => [
        'values' => ['color' => '#123456', 'typography' => ['custom' => ['customTypography' => ['fontSize' => ['style' => '21px']]]]],
        'labels' => ['color' => '#654321'],
    ]]],
    'design' => ['form_elements' => [
        'field_spacing' => ['margin_bottom' => ['style' => '24px']],
        'labels' => [
            'primary_spacing' => ['margin_bottom' => ['style' => '0px']],
            'primary_typography' => ['color' => '#abcdef', 'typography' => ['custom' => ['customTypography' => ['fontSize' => ['style' => '19px']]]]],
        ],
    ]],
];
$css = $twig->runTwig( $template, $properties );
$aliases = [];
preg_match_all( '/(--bdgf-stripe-[\w-]+):\s*([^;]+);/', $css, $matches, PREG_SET_ORDER );
foreach ( $matches as $match ) $aliases[$match[1]] = trim( $match[2] );
foreach ( [
    '--bdgf-stripe-input-color' => '#123456',
    '--bdgf-stripe-input-font-size' => '21px',
    '--bdgf-stripe-label-color' => '#abcdef',
    '--bdgf-stripe-label-font-size' => '19px',
    '--bdgf-stripe-label-margin-bottom' => '0px',
    '--bdgf-stripe-row-gap' => '24px',
] as $name => $expected ) {
    $check( ( $aliases[$name] ?? null ) === $expected, 'Global/element override failed: ' . $name );
}
echo "Passed {$checks} Breakdance Stripe template checks.\n";
