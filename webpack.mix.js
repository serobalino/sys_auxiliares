let mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */


mix
    .js('resources/assets/js/asecont.js', 'public/js')
    .js('resources/assets/js/invitado.js', 'public/js')
    .scripts(//tema
        [
            './node_modules/jquery/dist/jquery.js',
            "./node_modules/popper.js/dist/umd/popper.js",
            './node_modules/bootstrap/dist/js/bootstrap.min.js',
            './node_modules/bootstrap-select/js/bootstrap-select.js',
            './node_modules/bootstrap-select/js/i18n/defaults-es_ES.js',
            './node_modules/moment/moment.js',
            './node_modules/moment/locale/es.js',
            './node_modules/bootstrap-daterangepicker/daterangepicker.js',
            './node_modules/owl.carousel/dist/owl.carousel.js',
            './node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.js',

            './resources/assets/tema/src/js/framework/base/app.js',
            './resources/assets/tema/src/js/framework/base/util.js',
            './resources/assets/tema/src/js/framework/components/general/*.js',
            './resources/assets/tema/src/js/demo/demo10/base/layout.js',
            './resources/assets/tema/src/js/app/base/main.js',
        ],
        './public/js/asecont.vendor.js'
    )
    .webpackConfig({ devtool: "source-map" })
    .sass('resources/assets/sass/asecont.scss', 'public/css')
    .sass('resources/assets/sass/invitado.scss', 'public/css')
    .copyDirectory('resources/assets/logos', 'public/images/logos');
