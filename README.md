# Breakdance Gravityforms
This is a Wordpress plugin which gives Gravity Forms the ability to be styled within Breakdance. 

## TODO
- Work through integrations with payment processors: Square, PayPal

## Stripe styling
Use Gravity Forms' Orbital form theme with the Stripe Payment Element. The plugin
passes Breakdance CSS variables through `gform_stripe_elements_style`; the Stripe
add-on resolves them on the containing form before calling Stripe's public
[Appearance API](https://docs.stripe.com/elements/appearance-api).
Field colors, borders, padding, focus states, spacing, and typography inherit the
Breakdance form settings. Element-specific label typography and spacing take
precedence over global settings. Regenerate Breakdance CSS after upgrading.

Stripe Add-On 7.0+ also exposes `gform/stripe/elements/config/`. The small font
adapter uses this hook to pass matching, already-loaded webfont stylesheets to
Stripe's `fonts` option. It does not modify payment settings or access iframe DOM.
Font files must be publicly reachable and permit cross-origin loading. Older
add-ons retain Appearance styling with system font fallbacks. Legacy Card
Elements use the separate Stripe Style API and a normal outer-field CSS box.

## Changelog
### 9/29/26 v0.7.1
- Output native Gravity Forms configuration for Breakdance embeds so Stripe 6.1+ receives the form's styling before its separate configuration request.
- Preserve configuration for multiple embedded forms on the same page.
### 9/29/26 v0.7.0
- Match Stripe's secure payment fields to the containing Breakdance form using the supported Appearance/Style APIs.
- Inherit global field typography and per-element labels, spacing, colors, borders, padding, and focus styles.
- Load existing webfonts through the Stripe Elements Fonts API on Stripe Add-On 7.0+.
- Scope styling to the correct form and restore the legacy Card Element style configuration.
- Checks: `php tests/buttons.php`, `php tests/stripe.php`, `node tests/stripe-fonts.cjs`, and `wp eval-file path/to/tests/stripe-twig.php`.
### 9/29/26 v0.6.6
- Apply Breakdance button classes server-side to Gravity Forms 3 submit, next, and previous buttons.
- Preserve native button content, submission attributes, and adjacent payment controls; retain legacy input-button support.
- Add regression coverage: `php tests/buttons.php`.
### 10/30/25 v0.6.4
- Update encoding to UTF-8 when doing DOM_Document replacement
### 10/1/25 v0.6.3
- Fix sub labels from not being able to be styled (added display:block)
### 9/22/25 v0.6.2
- Add feature to rewrite the gravity forms tab indecies so there are no collisions between forms. We use the form ID * 100 + the existing tab index to make things clean.
### 5/16/25 v0.6.1
- Add limited support for Breakdance Square (label class only)
### 5/6/25 v0.6.0
- Add support for GF_Field_Advanced_Date field (our own Gravity Forms FlatPickr date field)
### 4/17/25 v0.5.12
- Add proper handling of PayPal checkout. Fixes the accidental removal of the Paypal div that's inserted by a wp filter.
- Add size input for PayPal button size
- Add default PayPal button css
### 4/9/25 v0.5.11
- Fix for unset variable
### 3/27/25 v0.5.10
- Prevent uneccessary script from loading for modern date picker field
### 3/24/25 v0.5.9
- Fix loading of variables into Stripe for GF Stripe fields; we check that a value is set first
### 3/24/25 v0.5.8
- Add extra classes to the cropped visibility normalize rule.
### 3/17/25 v0.5.7
- Remove var dumps and err logs for debugging
### 3/17/25 v0.5.6
- Improve overall style compaibility
- Add styles for newer multi-choice field
- Add styles for pricing fields
### 3/17/25 version 0.5.5
- Bump version and tag
### 2/16/25 version 0.5.4
- Style improvements including (but not limited to) file uploader (multiple files), and I forget the rest.
### 1/23/25 version 0.5.3
- Add support for our new flatpickr field type (add class for label)
- Add native browser based date picker field
- Style updates
### 1/10/25 version 0.5.2
- Fix fatal error that would happen when you put a gravity form on a page with the gutenburg editor
### 11/5/24 version 0.5.1
- Add file uploader styling
- Add .gform_validation_error to style with plural verison of the class name
- Probably a few other things that were useful but not documented :-)
### 9/30/24 version 0.5
- Add styling for text things, like HTML
- Rewrite the element ssr to use FormStyler class for better extensibility
- Styling for save & continue button
- Styling for save & continue screen
### 9/23/24
- Tweak PHP so it doesn't affect plain Gravity Forms on a site alongside Breakdance Gravity Forms
- Add controls for label typography, margins, etc.
- Prefix our normalize.css for our element
### Version 0.4
- Add spacing to the description text in a radio/checkbox.
- Add multiselect support
- Fix confirmation message not spanning across entire div.
### Version 0.3
- Add spacing bars to element
- Change grid column auto in normalize to  flex: 1 1 auto; since the name field was overflowing the div horiziontally
- Add hide label exclusions
