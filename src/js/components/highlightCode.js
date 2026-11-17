export async function highlightCode() {
    const { default: hljs } = await import('highlight.js/lib/core');
    const grammars = {
        javascript: () => import('highlight.js/lib/languages/javascript'),
        typescript: () => import('highlight.js/lib/languages/typescript'),
        php: () => import('highlight.js/lib/languages/php'),
        twig: () => import('highlight.js/lib/languages/twig'),
        css: () => import('highlight.js/lib/languages/css'),
        xml: () => import('highlight.js/lib/languages/xml'),
        json: () => import('highlight.js/lib/languages/json'),
        bash: () => import('highlight.js/lib/languages/bash'),
        sql: () => import('highlight.js/lib/languages/sql'),
        yaml: () => import('highlight.js/lib/languages/yaml'),
    };
    const languages = new Set([...document.querySelectorAll('pre code')].map(code => [...code.classList].find(name => name.startsWith('language-'))?.slice(9)));
    // Twig's grammar supports embedded HTML; register it first when needed.
    if (languages.has('twig')) languages.add('xml');
    try {
        await Promise.all([...languages].filter(language => grammars[language]).map(async language => {
            const { default: grammar } = await grammars[language]();
            hljs.registerLanguage(language, grammar);
        }));
        document.querySelectorAll('pre code').forEach(code => {
            const language = [...code.classList].find(name => name.startsWith('language-'))?.slice(9);
            if (language && hljs.getLanguage(language)) hljs.highlightElement(code);
        });
    } catch (error) {
        // Plain escaped code stays readable if a language chunk cannot load.
        console.warn('Syntax highlighting could not be loaded.', error);
    }
}
