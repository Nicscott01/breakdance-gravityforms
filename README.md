# Breakdance Gravityforms
This is a Wordpress plugin which gives Gravity Forms the ability to be styled within Breakdance. 


## Changelog
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