
const mix = require('laravel-mix');
const path = require('path');

const themeDir = __dirname + "/";

/* Mix Plugins */
// require('laravel-mix-bundle-analyzer');
require('laravel-mix-criticalcss');
require('laravel-mix-purgecss');

let proxy_url = process.env.PRIMARY_SITE_URL;

mix.webpackConfig({
    resolve: {
        mainFields: ['module', 'main'], // default ['browser', 'module', 'main']
    },
    stats: {
        children: true,
    }
});

mix
    .setResourceRoot('/assets/')
    .setPublicPath('public/assets')
    .js('src/js/app.js', 'js/app.js')
    .vue()
    .sass('src/sass/styles.scss', 'css/styles.min.css')

    .options({
        processCssUrls: false,
        postCss: [
            require('cssnano')(),
        ],
        compilerOptions: {
          isCustomElement: (tag) => ['inview'].includes(tag),
        },
        hmrOptions: {
            host: 'localhost',
            port: '3000'
        },
    })
    // .purgeCss({
    //     content: [path.join(__dirname, 'templates/**/*.twig')],
    //     enabled: false,
    // });

    // .purgeCss({
    //     content: [
    //         themeDir + 'templates/**/*.twig',
    //         // Specify path to own javascript
    //         themeDir + 'public/assets/js/**/*.js',
    //     ],
    //     enabled: mix.inProduction(),
    // })

// Browser sync for local development.
mix.browserSync({

    proxy: proxy_url,
    notify: {
        styles: {
            top: 'auto',
            bottom: '1rem'
        }
    },
    files: [
        'public/assets/images',
        'public/assets/css',
        'public/assets/js',
        'templates/**/*.twig',
    ]
});


if (mix.inProduction()) {
    // Disabled notifications on production builds.
    mix.disableNotifications();

    // Version all dynamic assets.
    mix.version();
}
