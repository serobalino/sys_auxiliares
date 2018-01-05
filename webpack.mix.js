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

mix //dependencias de usuario
    .copyDirectory('resources/assets/usuario/img','public/img')
    .scripts(
        [
            'node_modules/jquery/dist/jquery.min.js',
            'resources/assets/usuario/js/bootstrap.min.js',
            'resources/assets/usuario/js/material.min.js',
            'node_modules/chartist/dist/chartist.min.js',
            'node_modules/arrive/minified/arrive.min.js',
            'node_modules/perfect-scrollbar/dist/js/perfect-scrollbar.jquery.min.js',
            'node_modules/bootstrap-notify/bootstrap-notify.min.js',
            'resources/assets/usuario/js/material-dashboard.js'
        ],  'public/js/usuario.vendor.js')
    .js('resources/assets/js/usuario.js', 'public/js')
    .sass('resources/assets/sass/usuario.scss', 'public/css')
    //dependecias de invitado
    .copyDirectory('resources/assets/invitado/img','public/img')
    .scripts(
        [
            'node_modules/jquery/dist/jquery.min.js',
            'resources/assets/invitado/js/bootstrap.min.js',
            'resources/assets/invitado/js/material.min.js',
            'resources/assets/invitado/js/nouislider.min.js',
            'resources/assets/invitado/js/bootstrap-datepicker.js',
            'resources/assets/invitado/js/material-kit.js'
        ],  'public/js/invitado.vendor.js')
    .js('resources/assets/js/invitado.js', 'public/js')
    .sass('resources/assets/sass/invitado.scss', 'public/css')
    .sourceMaps();
