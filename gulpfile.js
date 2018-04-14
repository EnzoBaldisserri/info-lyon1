const gulp = require('gulp');
const sass = require('gulp-sass');
const stylelint = require('gulp-stylelint');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const babel = require('gulp-babel');
const eslint = require('gulp-eslint');
const imagemin = require('gulp-imagemin');
const sourcemaps = require('gulp-sourcemaps');

const resourcesPath = 'resources/';
const publicPath = 'web/';

const path = {
  nodeModules: 'node_modules/',
  in: {
    js: `${resourcesPath}/js/**/*.js`,
    scss: [
      `${resourcesPath}/scss/**/*.scss`,
      `!${resourcesPath}/scss/materialize/**/*`,
    ],
    images: `${resourcesPath}/images/**/*.*`,
  },
  out: {
    scripts: `${publicPath}/scripts/`,
    styles: `${publicPath}/styles/`,
    images: `${publicPath}/images/`,
  },
};

const sassConfig = {
  input: path.in.scss,
  output: path.out.styles,
  options: {
    outputStyle: 'expanded',
  },
};

const stylelintConfig = {
  input: path.in.scss,
  options: {
    reporters: [
      { formatter: 'string', console: true },
    ],
  },
};

const babelConfig = {
  input: path.in.js,
  output: path.out.scripts,
  options: {
    presets: ['env'],
  },
};

gulp.task('default', ['dev', 'watch']);

gulp.task('dev', ['ensure-dependencies', 'build-js', 'build-css', 'optimize-images']);

gulp.task('ensure-dependencies', ['move-materialize-js']);

gulp.task('move-materialize-js', () =>
  gulp
    .src(`${path.nodeModules}/materialize-css/dist/js/materialize.js`)
    .pipe(gulp.dest(path.out.scripts)));

gulp.task('build-js', () =>
  gulp
    .src(babelConfig.input)
    .pipe(sourcemaps.init())
    .pipe(babel(babelConfig.options))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(babelConfig.output)));

gulp.task('lint-js', () =>
  gulp
    .src(babelConfig.input)
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError()));

gulp.task('build-css', () =>
  gulp
    .src(sassConfig.input)
    .pipe(sourcemaps.init())
    .pipe(sass(sassConfig.options).on('error', sass.logError))
    .pipe(postcss([autoprefixer()]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(sassConfig.output)));

gulp.task('lint-css', () =>
  gulp
    .src(stylelintConfig.input)
    .pipe(stylelint(stylelintConfig.options)));

gulp.task('optimize-images', () =>
  gulp
    .src(path.in.images)
    .pipe(imagemin())
    .pipe(gulp.dest(path.out.images)));

gulp.task('watch', () => {
  gulp.watch(babelConfig.input, ['lint-js', 'build-js']);
  gulp.watch(sassConfig.input, ['lint-css', 'build-css']);
  gulp.watch(path.in.images, ['optimize-images']);
});
