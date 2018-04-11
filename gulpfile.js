const gulp = require('gulp');
const sass = require('gulp-sass');
const stylelint = require('gulp-stylelint');
const postcss = require('gulp-postcss');
const autoprefixer = require('autoprefixer');
const babel = require('gulp-babel');
const eslint = require('gulp-eslint');
const imagemin = require('gulp-imagemin');
const sourcemaps = require('gulp-sourcemaps');

const nodeModulesPath = 'node_modules/';
const pathResources = 'resources/';
const pathPublic = 'web/';

const sassConfig = {
  input: [
    `${pathResources}/scss/**/*.scss`,
    `!${pathResources}/scss/materialize/**/*`,
  ],
  output: `${pathPublic}/css/`,
  options: {
    outputStyle: 'expanded'
  }
}

const babelConfig = {
  input: `${pathResources}/js/**/*.js`,
  output: `${pathPublic}/js/`,
  options: {
    presets: ['env']
  }
}

gulp.task('default', ['dev', 'watch']);

gulp.task('dev', ['ensure-dependencies', 'build-js', 'build-css', 'optimize-images']);

gulp.task('ensure-dependencies', ['move-materialize-js']);

gulp.task('move-materialize-js', () => {
  return gulp
    .src(`${nodeModulesPath}/materialize-css/dist/js/materialize.js`)
    .pipe(gulp.dest(`${pathPublic}/js/`));
});

gulp.task('build-js', () => {
  return gulp
    .src(babelConfig.input)
    .pipe(sourcemaps.init())
      .pipe(babel(babelConfig.options))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(babelConfig.output));
});

gulp.task('lint-js', () => {
    return gulp
        .src(babelConfig.input)
        .pipe(eslint())
        .pipe(eslint.format())
        .pipe(eslint.failAfterError());
});

gulp.task('build-css', () => {
  return gulp.src(sassConfig.input)
    .pipe(sourcemaps.init())
      .pipe(sass(sassConfig.options).on('error', sass.logError))
      .pipe(postcss([ autoprefixer() ]))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(sassConfig.output));
});

gulp.task('lint-css', () => {
  return gulp
    .src(sassConfig.input)
    .pipe(stylelint({
      reporters: [
        { formatter: 'string', console: true }
      ]
  }));
});

gulp.task('optimize-images', () => {
  return gulp
    .src(`${pathResources}/images/*.*`)
    .pipe(imagemin())
    .pipe(gulp.dest(`${pathPublic}/images/`));
})

gulp.task('watch', () => {
  gulp.watch(babelConfig.input, ['lint-js', 'build-js']);
  gulp.watch(sassConfig.input, ['lint-css', 'build-css']);
  gulp.watch(`${pathResources}/images/*.*`, ['optimize-images']);
});
