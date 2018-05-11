const path = require('path');
const NotifierPlugin = require('webpack-notifier');
const CopyPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

module.exports = {
  mode: 'development',

  entry: {
    app: './assets/js/app.js',
    fos_layout: './assets/js/fos_layout.js',
    'absence-table': './assets/js/absence-table/index.js',
  },

  output: {
    path: path.resolve(__dirname, 'public/build/'),
    filename: '[name].js',
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules/,
        use: [
          'babel-loader',
          'eslint-loader',
        ],
      },
      {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              minimize: true,
            },
          },
          {
            loader: 'postcss-loader',
          },
          {
            loader: 'sass-loader',
            options: {
              outputStyle: 'expanded',
            },
          },
        ],
      },
    ],
  },

  plugins: [
    new NotifierPlugin(),
    new CopyPlugin([
      'node_modules/materialize-css/dist/js/materialize.js',
      { from: 'assets/images', to: '../images' },
    ]),
    new MiniCssExtractPlugin(),
  ],

  resolve: {
    extensions: ['.js', '.jsx', '.json'],
  },

  devtool: 'source-map',

  target: 'web',

  watch: true,
  watchOptions: {
    ignored: /node_modules/,
  },
};
