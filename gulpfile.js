var gulp = require('gulp');
var front = require('./gulp.front.js');
var admin = require('./gulp.admin.js');

// Register front-end tasks
front();

// Register back-end tasks
admin();

// Register global tasks
gulp.task(
  'deploy',
  gulp.parallel([
    // Back-end
    'admin-css',
    'admin-css-remote',
    'admin-js',
    'admin-js-remote',
    // Front-end
    'front-css',
    'front-css-remote',
    'front-js',
    'front-js-remote',
  ])
);

gulp.task(
  'watch',
  gulp.series(() => {
    // Back-end
    gulp.watch(
      ['www/assets/admin/css/**/*.css', 'www/assets/admin/css/**/*.less'],
      gulp.parallel(['admin-css'])
    );
    gulp.watch(['www/assets/admin/js/**/*.js'], gulp.parallel(['admin-js']));
    // Front-end
    gulp.watch(
      ['www/assets/front/css/**/*.css', 'www/assets/front/css/**/*.less'],
      gulp.parallel(['front-css'])
    );
    gulp.watch(['www/assets/front/js/**/*.js'], gulp.parallel(['front-js']));
  })
);
