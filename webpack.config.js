const Encore = require('@symfony/webpack-encore');

const entries = {
  app: 'app.js',
  login: 'login.js',
  'absence-table': 'absence-table/index.js',
};

Encore
  // the project directory where all compiled assets will be stored
  .setOutputPath('public/build/')

  // the public path used by the web server to access the previous directory
  .setPublicPath('/build')

  // enable source maps during development
  .enableSourceMaps(!Encore.isProduction())

  // empty the outputPath dir before each build
  .cleanupOutputBeforeBuild()

  // show OS notifications when builds finish/fail
  .enableBuildNotifications()

  .addLoader({
    test: /\.jsx?$/,
    exclude: /node_modules/,
    loader: 'eslint-loader',
  })

  // enable react
  .enableReactPreset()

  // allow sass/scss files to be processed
  .enableSassLoader((options) => {
    // eslint-disable-next-line no-param-reassign
    options.outputStyle = Encore.isProduction() ? 'compressed' : 'expanded';
  })

  // add postcss plugins
  .enablePostCssLoader();

Object.entries(entries).forEach(([name, path]) => {
  // will create public/build/[path].js and public/build/[path].css if needed
  Encore.addEntry(name, `./assets/js/${path}`);
});

const config = Encore.getWebpackConfig();
config.watchOptions = { ignored: /node_modules/ };

// export the final configuration
module.exports = config;
