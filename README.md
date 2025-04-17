# Breakdance Gravityforms
This is a Wordpress plugin which gives Gravity Forms the ability to be styled within Breakdance. 


## Changelog
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