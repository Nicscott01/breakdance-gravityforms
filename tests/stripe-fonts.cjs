// Run with: node tests/stripe-fonts.cjs
const assert = require('node:assert/strict');
const fs = require('node:fs');
const vm = require('node:vm');
const source = fs.readFileSync(__dirname + '/../assets/js/stripe-fonts.js', 'utf8');
const callbacks = [];
let hasStripe = true;
const fontSheet = (href, family) => ({ href, cssRules: [{ type: 5, style: { fontFamily: family } }] });
const googleSheet = { href: 'https://fonts.googleapis.com/css2?family=Carlito' };
Object.defineProperty(googleSheet, 'cssRules', { get() { throw new Error('Cross-origin CSSOM'); } });
const document = {
    querySelector: () => hasStripe,
    styleSheets: [
        fontSheet('https://example.test/fonts.css', '"Carlito"'),
        fontSheet('https://example.test/unrelated.css', 'Other Font'),
        fontSheet(null, 'Carlito'),
        googleSheet,
    ],
};
const gform = { utils: { addAsyncFilter: (name, callback) => {
    assert.equal(name, 'gform/stripe/elements/config/');
    callbacks.push(callback);
} } };
vm.runInNewContext(source, { window: { gform }, document, CSSRule: { FONT_FACE_RULE: 5 }, URL });
assert.equal(callbacks.length, 1);
const config = {
    mode: 'payment', amount: 2000, currency: 'usd', captureMethod: 'automatic',
    appearance: { variables: { fontFamily: 'Carlito, sans-serif' } },
    fonts: [{ cssSrc: 'https://example.test/fonts.css' }, { family: 'Custom', src: 'url(custom.woff2)' }],
};
const before = JSON.stringify(config);
const result = callbacks[0](config);
assert.equal(JSON.stringify(config), before, 'Do not mutate the original payment configuration');
assert.equal(result.fonts.length, 3, 'Deduplicate matching CSS; preserve existing font sources');
assert.equal(result.fonts[2].cssSrc, googleSheet.href);
assert.equal(result.appearance, config.appearance);
assert.equal(JSON.stringify({ ...result, fonts: config.fonts }), before, 'Only the fonts option may change');
hasStripe = false;
assert.equal(callbacks[0](config), config, 'Leave non-BDGF forms alone');

let listener;
const delayedWindow = {};
vm.runInNewContext(source, {
    window: delayedWindow,
    document: { addEventListener: (name, callback, options) => {
        assert.equal(name, 'gform/theme/scripts_loaded');
        assert.equal(options.once, true);
        listener = callback;
    } },
});
assert.equal(typeof listener, 'function');
delayedWindow.gform = gform;
listener();
assert.equal(callbacks.length, 2, 'Register when GF finishes loading');
console.log('Passed Stripe font configuration and load-order checks.');
