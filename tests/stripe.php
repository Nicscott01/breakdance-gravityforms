<?php
/** Run with: php tests/stripe.php */
require __DIR__ . '/../inc/StripeStyles.php';
require __DIR__ . '/../inc/FormStyler.php';

class GFStripe {}
$config_requests = [];
function do_action( $hook, $args ) {
    global $config_requests;
    if ( $hook === 'gform_output_config' ) $config_requests[] = $args;
}

$checks = 0;
function check( $condition, $message ) {
    global $checks;
    $checks++;
    if ( !$condition ) throw new RuntimeException( $message );
}

// Multiple form instances share the GF filter. Only the target may replace it.
$reflection = new ReflectionClass( BDGF\FormStyler::class );
$first = $reflection->newInstanceWithoutConstructor();
$first->form_id = '9';
$second = $reflection->newInstanceWithoutConstructor();
$second->form_id = 10;
$unrelated = ['theme' => 'night', 'variables' => ['colorPrimary' => '#123456']];
check( $first->style_stripe_form( $unrelated, 11, true ) === $unrelated, 'Changed an unrelated form' );
$payment = $first->style_stripe_form( $unrelated, 9, true );
check( $second->style_stripe_form( $payment, 9, true ) === $payment, 'A second form overwrote the first' );
$first->enqueue_stripe_config( ['id' => 11] );
check( !$config_requests, 'Localized config for an unrelated form' );
$first->enqueue_stripe_config( ['id' => 9] );
$second->enqueue_stripe_config( ['id' => 10] );
$first->enqueue_stripe_config( ['id' => 9] );
check( end( $config_requests ) === ['form_ids' => [9, 10]], 'Config localization lost another embedded form or duplicated IDs' );
check( in_array( $payment['theme'], ['stripe', 'night', 'flat'], true ), 'Unsupported Stripe theme' );
check( !isset( $payment['base'] ), 'Mixed legacy Style and Appearance APIs' );

$card = $first->style_stripe_form( $unrelated, 9, false );
check( !empty( $card['base']['fontFamily'] ) && !empty( $card['base']['color'] ), 'Legacy Card style was discarded' );
check( !isset( $card['theme'], $card['variables'], $card['rules'] ), 'Appearance supplied to the legacy Card Element' );

// These public selectors/properties are supported by the Stripe Appearance API.
$selectors = ['.Input', '.Input:focus', '.Input:hover:focus', '.Input--invalid', '.Input::placeholder', '.Label', '.Tab', '.Tab--selected', '.Error'];
$properties = ['backgroundColor', 'borderColor', 'borderWidth', 'borderStyle', 'borderRadius', 'boxShadow', 'paddingTop', 'paddingRight', 'paddingBottom', 'paddingLeft', 'fontFamily', 'fontSize', 'fontWeight', 'lineHeight', 'letterSpacing', 'color', 'marginBottom'];
foreach ( $payment['rules'] as $selector => $rules ) {
    check( in_array( $selector, $selectors, true ), 'Unsupported public selector: ' . $selector );
    check( !array_diff( array_keys( $rules ), $properties ), 'Unsupported Appearance property' );
}
echo "Passed {$checks} Stripe checks.\n";
