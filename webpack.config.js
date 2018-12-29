var Encore = require('@symfony/webpack-encore');

Encore
// directory where compiled assets will be stored
  .setOutputPath('assets/build')
// public path used by the web server to access the output path
  .setPublicPath('assets/build')
// only needed for CDN's or sub-directory deploy
// .setManifestKeyPrefix('build/')

/*
     * ENTRY CONFIG
     *
     * Add 1 entry for each "page" of your app
     * (including one that's included on every page - e.g. "app")
     *
     * Each entry will result in one JavaScript file (e.g. app.js)
     * and one CSS file (e.g. app.scss) if you JavaScript imports CSS.
     */

  .addEntry('app', './assets/js/app.js')
  .addEntry('presentation', './assets/js/presentation.js')
  .addEntry('dashboard', './assets/js/dashboard/main.js')

/*
     * FEATURE CONFIG
     *
     * Enable & configure other features below. For a full
     * list of features, see:
     * https://symfony.com/doc/current/frontend.html#adding-more-features
     */
  .cleanupOutputBeforeBuild()
  .enableBuildNotifications()
  .enableSourceMaps(!Encore.isProduction())
// enables hashed filenames (e.g. app.abc123.scss)
  .enableVersioning(Encore.isProduction())
  .enableSingleRuntimeChunk()

  .addLoader({
    test: /\.(js)$/,
    loader: 'eslint-loader',
    exclude: [/node_modules/],
    enforce: 'pre',
    options: {
      configFile: './.eslintrc',
      emitWarning: true
    }
  })

// enables Sass/SCSS support
  .enableSassLoader(function (sassOptions) {
  }, {
    resolveUrlLoader: false
  })

// uncomment if you use TypeScript
//    .enableTypeScriptLoader()

  .enableReactPreset()

//  .enableEslintLoader()

// uncomment if you're having problems with a jQuery plugin
  .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
