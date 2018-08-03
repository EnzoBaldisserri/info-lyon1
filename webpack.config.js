const path = require('path');
const NotifierPlugin = require('webpack-notifier');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');

const production = process.env.APP_ENV === 'prod';

module.exports = {
  mode: 'development',

  entry: {
    'absence-table': './assets/js/absence-table/index.js',
    app: './assets/js/app.js',
    semester_form: './assets/js/administration/semester_form.js',
  },

  output: {
    path: path.resolve(__dirname, 'public/build/'),
    filename: '[name].js',
  },

  module: {
    rules: [
      {
        test: /\.jsx?$/,
        exclude: /node_modules|public/,
        use: [
          'babel-loader',
          'eslint-loader',
        ],
      },
      {
        test: /\.scss$/,
        exclude: /node_modules|public/,
        use: [
          MiniCssExtractPlugin.loader,
          {
            loader: 'css-loader',
            options: {
              sourceMap: !production,
              minimize: production,
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
    new MiniCssExtractPlugin(),
  ],

  resolve: {
    extensions: ['.js', '.jsx', '.json'],
  },

  devtool: production ? false : 'source-map',

  target: 'web',

  watchOptions: {
    ignored: /node_modules/,
  },
};
