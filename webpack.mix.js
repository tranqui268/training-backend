const mix = require("laravel-mix");

mix.js("resources/js/app.js", "public/js")
    .vue({ version: 3 })
    .sass("resources/sass/app.scss", "public/css")
    .postCss("resources/css/app.css", "public/css", [
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .options({
        processCssUrls: false,
    });

if (mix.inProduction()) {
    mix.version();
}

mix.options({
    hmrOptions: {
        host: "localhost",
        port: 8080,
    },
});

mix.webpackConfig({
    resolve: {
        alias: {
            "@": __dirname + "/resources/js",
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
});
