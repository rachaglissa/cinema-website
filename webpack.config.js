// webpack.config.js

const Encore = require('@symfony/webpack-encore');

Encore
    // directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // public path used by the web server to access the output path
    .setPublicPath('/build')
    // only needed for CDN's or sub-directory deploy
    .enableSingleRuntimeChunk()
    // the main CSS file
    .addStyleEntry('app_style', '/assets/styles/app.css')
    // the main JS file
    .addEntry('app', '/assets/app.js')
    // enables Sass/SCSS support
    .enableSassLoader()
    // enable versioning (e.g. app.css -> app.abc123.css)
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
