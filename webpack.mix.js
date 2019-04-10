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
mix.scripts([
   'public/bootstrap/js.js',
   'public/js/jquery-ui/jquery-ui.min.js',
   'public/bootstrap/js/bootstrap.bundle.min.js',
   'public/js/script.js',
   'public/js/drugs.js',
   'public/js/globalize.js',
   'public/js/patients.js',
   'public/js/users.js',
   'public/js/validate.js',
   'public/js/visits.js',
   'public/js/xrays.js',
   'public/js/js_barcode.min.js',
   'public/js/diagnoses.js',
], 'public/js/all.js');

mix.styles([
   'public/js/jquery-ui/jquery-ui.min.css',
   'public/js/jquery-ui/jquery-ui.theme.min.css',
   'public/bootstrap/css/bootstrap.min.css',
   'public/css/style.css'
], 'public/css/all.css');