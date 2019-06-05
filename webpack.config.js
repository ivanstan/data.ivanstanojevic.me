const Encore = require('@symfony/webpack-encore');
const path = require("path");

Encore
// directory where compiled assets will be stored
  .setOutputPath('public/build')
  // public path used by the web server to access the output path
  .setPublicPath('public')
  // only needed for CDN's or sub-directory deploy
  // .setManifestKeyPrefix('public/build/')

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
    exclude: [/node_modules/, /libs/],
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

  .enableTypeScriptLoader()

  .enableReactPreset()

  .enableEslintLoader()
  .enableForkedTypeScriptTypesChecking()

  // uncomment if you're having problems with a jQuery plugin
  .autoProvidejQuery()

  .copyFiles([
    {
      from: './assets/images',
      // optional target path, relative to the output dir
      // to: 'images/[path][name].[ext]',
      // if versioning is enabled, add the file hash too
      to: './images/[path][name].[ext]',
      // only copy files matching this pattern
      // pattern: /\.(png|jpg|jpeg)$/
    },
  ])
;

let config = Encore.getWebpackConfig();

config.resolve.alias = path.join(path.resolve(__dirname, "..", "assets"));

module.exports = config;
