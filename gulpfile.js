var gulp = require('gulp');
var front = require('./gulp.front.js');
var admin = require('./gulp.admin.js');

// Register front-end tasks
front();

// Register back-end tasks
admin();

// Register global tasks
gulp.task('deploy', [
    // Back-end
    'admin-css',
    'admin-css-remote',
    'admin-js',
    'admin-js-remote',
    // Front-end
    'front-css',
    'front-css-remote',
    'front-js',
    'front-js-remote'
]);

gulp.task('watch', function () {
    // Back-end
    gulp.watch(['www/assets/admin/css/**/*.css', 'www/assets/admin/css/**/*.less'], ['admin-css']);
    gulp.watch(['www/assets/admin/js/**/*.js'], ['admin-js']);
    // Front-end
    gulp.watch(['www/assets/front/css/**/*.css', 'www/assets/front/css/**/*.less'], ['front-css']);
    gulp.watch(['www/assets/front/js/**/*.js'], ['front-js']);
});
