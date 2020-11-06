const mix = require('laravel-mix');

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

mix.styles([
    'resources/js/plantilla/css/font-awesome.min.css',
    'resources/js/plantilla/css/simple-line-icons.min.css',
    'resources/js/plantilla/css/style.css'
], 'public/css/plantilla.css')
.scripts([
    'resources/js/plantilla/js/jquery.min.js',
    'resources/js/plantilla/js/popper.min.js',
    'resources/js/plantilla/js/bootstrap.min.js',
    'resources/js/plantilla/js/Chart.min.js',
    'resources/js/plantilla/js/pace.min.js',
    'resources/js/plantilla/js/template.js',
    'resources/js/plantilla/js/sweetalert2.all.min.js'
], 'public/js/plantilla.js')
.js(['resources/js/app.js'], 'public/js/app.js');
