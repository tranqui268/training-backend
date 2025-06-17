const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .vue({version: 2})
    .sass('resources/sass/app.scss', 'public/css')
    .options({
        processCssUrls: false
    });

if (mix.inProduction()) {
    mix.version();   
}

mix.options({
    hmrOptions: {
        host: 'localhost',
        port: 8080,
    }
});

mix.webpackConfig({
    resolve: {
        alias: {
            '@': __dirname + '/resources/js',
        },
    },
});