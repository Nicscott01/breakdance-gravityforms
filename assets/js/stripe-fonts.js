/* Supply existing page webfonts through Stripe's supported Elements fonts option. */
(() => {
    'use strict';

    const register = () => {
        if (!window.gform?.utils?.addAsyncFilter) return;

        window.gform.utils.addAsyncFilter('gform/stripe/elements/config/', (config) => {
            if (!document.querySelector('.bdgf .gfield--type-stripe_creditcard')) return config;

            const appearance = config.appearance || {};
            const families = new Set([
                appearance.variables?.fontFamily || '',
                ...Object.values(appearance.rules || {}).map(rule => rule.fontFamily || ''),
            ].flatMap(value => value.split(',').map(family => family.trim().replace(/["']/g, '').toLowerCase())));
            const sources = new Set();

            // Reuse the site's actual font CSS, including locally hosted fonts.
            // CSSOM access is read-only and never touches Stripe's iframe.
            for (const sheet of document.styleSheets) {
                if (!sheet.href) continue;
                try {
                    const matches = [...sheet.cssRules].some(rule =>
                        rule.type === CSSRule.FONT_FACE_RULE &&
                        families.has(rule.style.fontFamily.replace(/["']/g, '').toLowerCase())
                    );
                    if (matches) sources.add(sheet.href);
                } catch {
                    // Cross-origin Google Fonts CSS cannot be read through CSSOM,
                    // but Stripe accepts its stylesheet URL as a CSS font source.
                    if (new URL(sheet.href).hostname === 'fonts.googleapis.com') sources.add(sheet.href);
                }
            }

            const fonts = [...(config.fonts || [])];
            for (const cssSrc of sources) {
                if (!fonts.some(font => font.cssSrc === cssSrc)) fonts.push({ cssSrc });
            }
            return fonts.length ? { ...config, fonts } : config;
        });
    };

    if (window.gform?.utils?.addAsyncFilter) register();
    else document.addEventListener('gform/theme/scripts_loaded', register, { once: true });
})();
