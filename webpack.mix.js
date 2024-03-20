const mix = require('laravel-mix');
const path = require('path');
const webpack = require('webpack');

mix.webpackConfig(
    {
        resolve: {
            alias: {
                '~': path.resolve(__dirname, 'node_modules'),
            },
            modules: [
                'node_modules',
            ],
        },
        plugins: [
            new webpack.ProvidePlugin(
                {
                    $: 'jquery',
                    jQuery: 'jquery',
                    'window.jQuery': 'jquery',
                },
            ),
        ],
    },
);

mix.options(
    {
        postCss: [
            require('postcss-discard-comments')
            (
                {
                    removeAll: true,
                },
            ),
        ],
        uglify: {
            comments: false,
        },
    },
);

mix
    .sass('resources/scss/site/ckeditor.scss', 'public/css/site/ckeditor.css')
    .version();

if(!mix.inProduction())
{
    mix.sourceMaps();
}

