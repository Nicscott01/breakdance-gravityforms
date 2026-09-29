<?php
/** Run with: php tests/buttons.php */
require __DIR__ . '/../inc/helper-functions.php';

$checks = 0;
function check( $condition, $message ) {
    global $checks;
    $checks++;
    if ( !$condition ) {
        throw new RuntimeException( $message );
    }
}

function fragment( $html ) {
    $dom = new DOMDocument();
    @$dom->loadHTML( '<?xml encoding="utf-8" ?><div>' . $html . '</div>' );
    return $dom;
}

$form = ['id' => 9];
$native = '<input type="hidden" name="provider" value="keep"><button type="button" id="paypal">PayPal</button>'
    . '<button id="gform_submit_button_9" class="gform_button button" type="submit" name="submit" value="donate" disabled aria-label="Donate" data-submission-type="submit" onclick="gform.submission.handleButtonClick(this);"><span>Donaté &amp; Give</span><span class="gform-loader"></span></button>'
    . '<div id="payment-container"><button type="button">Payment</button></div>';
$styled = BDGF\input_to_button( $native, $form );
$dom = fragment( $styled );
$button = $dom->getElementById( 'gform_submit_button_9' );
foreach ( ['gform_button', 'button', 'button-atom', 'button-atom--primary', 'breakdance-form-button', 'breakdance-form-button__submit'] as $class ) {
    check( in_array( $class, explode( ' ', $button->getAttribute( 'class' ) ), true ), 'Missing class: ' . $class );
}
foreach ( ['type' => 'submit', 'name' => 'submit', 'value' => 'donate', 'aria-label' => 'Donate', 'data-submission-type' => 'submit', 'onclick' => 'gform.submission.handleButtonClick(this);'] as $name => $value ) {
    check( $button->getAttribute( $name ) === $value, 'Changed attribute: ' . $name );
}
check( $button->hasAttribute( 'disabled' ), 'Lost disabled state' );
check( $button->textContent === 'Donaté & Give', 'Lost UTF-8 label or inner markup' );
check( $button->getElementsByTagName( 'span' )->length === 2, 'Lost label/spinner children' );
check( $dom->getElementById( 'payment-container' )->textContent === 'Payment', 'Lost payment sibling' );
check( !$dom->getElementById( 'paypal' )->hasAttribute( 'class' ), 'Styled unrelated provider button' );
check( $dom->getElementsByTagName( 'input' )->item( 0 )->getAttribute( 'value' ) === 'keep', 'Changed hidden sibling' );
check( BDGF\input_to_button( $styled, $form ) === $styled, 'Styling must be idempotent' );

foreach ( ['next', 'previous'] as $direction ) {
    $html = '<button id="gform_' . $direction . '_button_9_31" type="button" data-submission-type="' . $direction . '">Continue</button>';
    $html = BDGF\button_secondary( BDGF\input_to_button( $html, $form ), $form );
    $control = fragment( $html )->getElementsByTagName( 'button' )->item( 0 );
    check( strpos( $control->getAttribute( 'class' ), 'button-atom--secondary' ) !== false, 'Pagination variant missing' );
    check( strpos( $control->getAttribute( 'class' ), 'breakdance-form-button__submit' ) === false, 'Pagination misidentified as submit' );
    check( $control->getAttribute( 'data-submission-type' ) === $direction, 'Lost pagination behavior' );
}

foreach ( ['primary', 'secondary', 'custom', 'text'] as $variant ) {
    $callback = 'BDGF\\button_' . $variant;
    $html = $callback( $styled, $form );
    check( strpos( fragment( $html )->getElementById( 'gform_submit_button_9' )->getAttribute( 'class' ), 'button-atom--' . $variant ) !== false, 'Missing variant: ' . $variant );
}

$legacy = '<input type="hidden" value="keep"><input type="submit" id="gform_submit_button_9" value="Donaté &amp; Give" onclick="legacy();" data-test="keep"><div id="paypal-container"></div>';
$dom = fragment( BDGF\input_to_button( $legacy, $form ) );
$button = $dom->getElementById( 'gform_submit_button_9' );
check( $button->tagName === 'button' && $button->textContent === 'Donaté & Give', 'Legacy conversion failed' );
check( $button->getAttribute( 'onclick' ) === 'legacy();' && $button->getAttribute( 'data-test' ) === 'keep', 'Legacy attributes lost' );
check( $dom->getElementById( 'paypal-container' ) !== null, 'Legacy payment sibling lost' );
check( strpos( $button->getAttribute( 'class' ), 'button-atom' ) !== false, 'Classless legacy button not styled' );

$image = '<input type="image" id="gform_submit_button_9" src="donate.png" alt="Donate">';
$button = fragment( BDGF\input_to_button( $image, $form ) )->getElementById( 'gform_submit_button_9' );
check( $button->getAttribute( 'type' ) === 'submit' && $button->getElementsByTagName( 'img' )->item( 0 )->getAttribute( 'alt' ) === 'Donate', 'Legacy image button lost' );
check( BDGF\input_to_button( '<div>Payment only</div>', $form ) === '<div>Payment only</div>', 'Rewrote unrelated markup' );
check( BDGF\input_to_button( $native, ['id' => 8] ) === $native, 'Styled a different form' );
echo "Passed {$checks} button checks.\n";
