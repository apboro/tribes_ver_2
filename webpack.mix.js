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
let path = require('path');

mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .sourceMaps();

mix.js('resources/js/courseEditor.js', 'public/js').vue();
mix.sass('resources/sass/courseEditor.scss', 'public/css');

mix.js('resources/js/knowledge.js', 'public/js').vue();
mix.sass('resources/sass/knowledgeStyles.scss', 'public/css');


mix.js('resources/admin/js/index.js', 'public/admin/js').vue();
mix.sass('resources/admin/css/index.scss', 'public/admin/css');

/* mix.js('resources/tribes/js/index.js', 'public/admin/js').vue();
mix.sass('resources/tribes/scss/index.scss', 'public/admin/css'); */
// mix.copyDirectory('resources/admin/img', 'public/admin/img');
module.exports = {
    module: {
        rules: [
            {
                test: /\.(png|jpe?g|gif|svg)(\?.*)?$/,
                use: [

                    'vue-loader',
                    'vue-svg-loader',
                ],
            },
        ],
    },
};
// module.exports = {
//     output: {
//         filename: '[name].js',
//         path: path.resolve(__dirname, 'js'),
//         publicPath: 'js/',
//         chunkFilename: '[name].js'
//     },
//     watch: true
// };


mix.copyDirectory('resources/images', 'public/images');
mix.copyDirectory('resources/ico', 'public/ico');
mix.copyDirectory('resources/fonts', 'public/fonts');
mix.copyDirectory('resources/videos', 'public/videos');

if(mix.inProduction() ) {
    // mix.minify('public/js/app.js');
    // mix.minify('public/css/app.css');
    // mix.minify('public/css/base.css');
    mix.version();
} else {
    mix.webpackConfig({
        devtool:"inline-source-map",
    });
    mix.sourceMaps();
}