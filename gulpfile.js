const gulp = require('gulp');
const sass = require('gulp-sass');
const babel = require('gulp-babel');
const sourcemaps = require('gulp-sourcemaps');

const sassConfig = {
  input: 'resources/scss/**/*.scss',
  output: 'web/css/',
  options: {
    outputStyle: 'expanded'
  }
}

const babelConfig = {
  input: 'resources/js/**/*.js',
  output: 'web/js/',
  options: {
    presets: ['env']
  }
}

gulp.task('default', ['build-js', 'build-css', 'watch']);

gulp.task('build-js', () => {
  gulp.src(babelConfig.input)
    .pipe(sourcemaps.init())
      .pipe(babel(babelConfig.options))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(babelConfig.output));
})

gulp.task('build-css', () => {
  return gulp.src(sassConfig.input)
    .pipe(sourcemaps.init())
      .pipe(sass(sassConfig.options).on('error', sass.logError))
    .pipe(sourcemaps.write('.'))
    .pipe(gulp.dest(sassConfig.output));
});

gulp.task('watch', () => {
  gulp.watch(babelConfig.input, ['build-js']);
  gulp.watch(sassConfig.input, ['build-css']);
});
