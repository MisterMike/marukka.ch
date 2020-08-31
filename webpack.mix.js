const mix = require('laravel-mix');


mix.setPublicPath('public/assets/')
    .js('dev/_js/main.js', 'js/main.min.js')
    .js('dev/_js/plugins.js', 'js/plugins.min.js')
    .sass('dev/_sass/styles.scss', 'css/styles.min.css')
    .sass('dev/_sass/_vendors/photoswipe/css/main.scss', 'plugins/photoswipe/photoswipe.css')
    .options({
        processCssUrls: false,
    })
  .copy('node_modules/owl.carousel/dist/assets/owl.carousel.min.css', 'public/assets/plugins/owl-carousel/owl.carousel.min.css')
  .copy('node_modules/owl.carousel/dist/owl.carousel.min.js', 'public/assets/plugins/owl-carousel/owl.carousel.min.js')
  .copy('node_modules/photoswipe/dist/photoswipe.min.js', 'public/assets/plugins/photoswipe/photoswipe.min.js')
  .copy('node_modules/photoswipe/dist/photoswipe-ui-default.min.js', 'public/assets/plugins/photoswipe/photoswipe-ui-default.min.js');
 

// mix.js('src/js/app.js', 'js/')
//    .sass('src/sass/app.scss', 'css/')
//    .autoload({
//        jquery: ['$', 'jQuery', 'jquery', 'window.jQuery'],
//        'popper.js/dist/umd/popper.js': ['Popper']
//    })
//    .extract(['jquery', 'popper.js', 'bootstrap'])
//    .options({
//        processCssUrls: false
//    });
 

// Browser sync for local development.
mix.browserSync({
    // When serving flat HTML from a given folder
    // proxy: false,
    // server: 'dist',
    
    // When serving PHP via xyz.localhost URL
    // Note, no server node required
    proxy: 'marukka.localhost',
  
    files: [
        'public/assets/img',
        'public/assets/css',
        'templates/**/*.twig',
    ]
});


if (mix.production) {
    // Disabled notifications on production builds.
    mix.disableNotifications();

    // Version all dynamic assets.
    mix.version();
}