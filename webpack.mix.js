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


 https://laravel.com/docs/7.x/mix#versioning-and-cache-busting

 .version()
 .disableNotifications()
 */

mix.js('resources/assets/js/app.js', 'public/js')
.version()
.disableNotifications();
