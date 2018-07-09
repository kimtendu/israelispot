const gulp              = require('gulp');
const concat            = require('gulp-concat');
const concatCss         = require('gulp-concat-css');
const uglify            = require('gulp-uglify');
const uglifycss         = require('gulp-uglifycss');
const sass              = require('gulp-sass');
const watch             = require('gulp-watch');



//BUILD IMAGES
gulp.task('img:build', function() {
    gulp.src(['src/img/*.*'])
        .pipe(gulp.dest('../img/'));
});
//BUILD FONTS
gulp.task('fonts:build', function() {
    gulp.src(['src/fonts/*.*'])
        .pipe(gulp.dest('../fonts/'));
});
//CONCAT .SCSS
gulp.task('scss:concat', function () {
    return gulp.src(['src/styles/sass/style.scss',
        'src/styles/sass/*.scss'])
        .pipe(concat('style.main.scss'))
        .pipe(gulp.dest('src/styles/'));
});
// .SCSS TO .CSS
gulp.task('sass',['scss:concat'], function () {
  return gulp.src('src/styles/style.main.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(gulp.dest('src/styles/css/'));
});
//CONCAT .CSS
gulp.task('css:concat', ['sass'], function () {
    return gulp.src(['src/styles/css/fonts.css',
        'src/styles/css/style.main.css'])
        .pipe(concatCss('style.main.css'))
        .pipe(gulp.dest('src/styles/'));
});

//MIN AND BUILD .CSS
gulp.task('css:compress', ['css:concat'], function () {
  gulp.src('src/styles/style.main.css')
    .pipe(uglifycss({
      "maxLineLen": 80,
      "uglyComments": true
    }))
    .pipe(concat('style.main.min.css'))
    .pipe(gulp.dest('../css'));
});
//CONCAT JS
gulp.task('js:concat', function() {
  return gulp.src(['src/js/libraries/*.js','src/js/script.js', 'src/js/*.js','!src/js/script.main.js'])
    .pipe(concat('script.main.js'))
    .pipe(gulp.dest('src/js/'));
});
//MIN AND BUILD JS
gulp.task('js:compress', ['js:concat'], function() {
  return gulp.src('src/js/script.main.js')
    .pipe(uglify())
    .pipe(concat('script.main.min.js'))
    .pipe(gulp.dest('../js/'));
});

//WATCH
gulp.task('watch', function () {
  gulp.watch('src/styles/sass/*.scss', ['scss:concat','sass', 'css:concat', 'css:compress']);
  gulp.watch('src/js/*.js', ['js:concat', 'js:compress']);
});

gulp.task('default',
        ['img:build',
         'fonts:build',
         'scss:concat',
         'sass',
         'css:concat',
         'css:compress',
         'js:concat',
         'js:compress'
         ], function () {
});