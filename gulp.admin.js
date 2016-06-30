var gulp = require('gulp');
var less = require('gulp-less');
var minifyCss = require('gulp-minify-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var order = require('gulp-order');
var remoteSrc = require('gulp-remote-src');

module.exports = function () {
    gulp.task('admin-css', function () {
        gulp.src('www/assets/admin/css/theme.less')
            .pipe(less())
            .pipe(minifyCss({compatibility: 'ie8'}))
            .pipe(concat('admin.bundle.css'))
            .pipe(gulp.dest('www/dist'));
    });

    gulp.task('admin-css-remote', function () {
        remoteSrc([
            'twitter-bootstrap/3.3.6/css/bootstrap.min.css'
        ], {
            base: 'https://cdnjs.cloudflare.com/ajax/libs/'
        })
            .pipe(minifyCss({compatibility: 'ie8'}))
            .pipe(concat('admin.external.css'))
            .pipe(gulp.dest('www/dist/'));
    });

    gulp.task('admin-js', function () {
        gulp.src([
                'www/assets/admin/js/**/*.js',
                'www/assets/vendor/**/*.js'
            ])
            .pipe(order([
                'nette/nette.forms.js',
                'nette/nette.ajax.js',
                'main.js'
            ]))
            .pipe(uglify())
            .pipe(concat('admin.bundle.js'))
            .pipe(gulp.dest('www/dist'));
    });

    gulp.task('admin-js-remote', function () {
        remoteSrc([
            'jquery/2.2.4/jquery.min.js',
            'twitter-bootstrap/3.3.6/js/bootstrap.min.js',
            'chosen/1.4.2/chosen.jquery.min.js'
        ], {
            base: 'https://cdnjs.cloudflare.com/ajax/libs/'
        })
            .pipe(uglify())
            .pipe(concat('admin.external.js'))
            .pipe(gulp.dest('www/dist/'));
    });
};
