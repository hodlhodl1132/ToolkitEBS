const mix = require('laravel-mix');
// const tailwindcss = require('tailwindcss');
// const semanticui = require('semantic-ui-css');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js').postCss('resources/css/app.css', 'public/css', [
    require('postcss-import'),
    require('autoprefixer'),
])
    .copyDirectory('semantic/dist/themes/','public/css/themes')
    .copy('semantic/dist/semantic.min.css', 'public/css/semantic.min.css')
    .copy('semantic/dist/semantic.min.js', 'public/js/semantic.min.js')