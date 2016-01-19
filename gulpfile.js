var gulp = require('gulp');
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var order = require('gulp-order');
var remoteSrc = require('gulp-remote-src');

gulp.task('css', function () {
    gulp.src('www/assets/css/theme.css')
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(concat('bundle.css'))
        .pipe(gulp.dest('www/dist'));
});

gulp.task('css-remote', function () {
    remoteSrc([
        'twitter-bootstrap/3.3.5/css/bootstrap.min.css',
        'highlight.js/8.8.0/styles/github.min.css',
        'font-awesome/4.4.0/css/font-awesome.min.css',
        'chosen/1.4.2/chosen.min.css'
    ], {
        base: 'https://cdnjs.cloudflare.com/ajax/libs/'
    })
        .pipe(minifyCss({compatibility: 'ie8'}))
        .pipe(concat('external.css'))
        .pipe(gulp.dest('www/dist/'));
});

gulp.task('js', function () {
    gulp.src('www/assets/js/**/*.js')
        .pipe(order([
            'nette/nette.forms.js',
            'nette/nette.ajax.js',
            'main.js'
        ]))
        .pipe(uglify())
        .pipe(concat('bundle.js'))
        .pipe(gulp.dest('www/dist'));
});

gulp.task('js-remote', function () {
    remoteSrc([
        'jquery/2.1.4/jquery.min.js',
        'twitter-bootstrap/3.3.5/js/bootstrap.min.js',
        'highlight.js/8.8.0/highlight.min.js',
        'typeahead.js/0.11.1/typeahead.bundle.min.js',
        'handlebars.js/4.0.3/handlebars.min.js',
        'chosen/1.4.2/chosen.jquery.min.js',
        'canvasjs/1.7.0/canvasjs.min.js',
        'clipboard.js/1.5.5/clipboard.min.js'
    ], {
        base: 'https://cdnjs.cloudflare.com/ajax/libs/'
    })
        .pipe(uglify())
        .pipe(concat('external.js'))
        .pipe(gulp.dest('www/dist/'));
});

gulp.task('deploy', [
    'css',
    'css-remote',
    'js',
    'js-remote'
]);

gulp.task('watch', function () {
    gulp.watch(['www/assets/css/**/*.css'], ['css']);
    gulp.watch(['www/assets/js/**/*.js'], ['js']);
});
