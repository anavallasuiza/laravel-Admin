// Load plugins
var gulp = require('gulp'),
    replace = require('gulp-replace'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rev = require('gulp-rev'),
    del = require('del');

var paths = {
    'from': {
        'css': './src/anavallasuiza/laravel-Admin/resources/assets/css/',
        'js': './src/anavallasuiza/laravel-Admin/resources/assets/js/',
        'vendor': './assets/vendor/',
    'adminlte': '../../almasaeed2010/adminlte/'
    },
    'to': {
        'build': './assets/build/',
        'css': './assets/build/css/',
        'js': './assets/build/js/'
    }
};

var directories = {};

directories[paths.from.vendor   + 'select2/*'] = paths.to.build + 'select2';
directories[paths.from.vendor   + 'bootstrap-fileinput/img/*'] = paths.to.build + 'bootstrap-fileinput/img',
directories[paths.from.vendor   + 'bootstrap/dist/fonts/*'] = paths.to.build + 'fonts';
directories[paths.from.vendor   + 'font-awesome/fonts/*'] = paths.to.build + 'fonts';
directories[paths.from.adminlte + 'plugins/datatables/images/*'] = paths.to.build + 'datatables/images';

var js_files = [
    paths.from.vendor   + 'jquery/dist/jquery.min.js',
    paths.from.vendor   + 'bootstrap/dist/js/bootstrap.min.js',
    paths.from.vendor   + 'bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.all.min.js',
    paths.from.vendor   + 'select2/select2.min.js',
    paths.from.vendor   + 'bootstrap-fileinput/js/fileinput.min.js',
    paths.from.vendor   + 'DataTables/media/js/jquery.dataTables.min.js',
    paths.from.adminlte + 'plugins/datatables/dataTables.bootstrap.js',
    paths.from.js + '*'
];

var css_files = [
    paths.from.vendor   + 'bootstrap/dist/css/bootstrap.min.css',
    paths.from.vendor   + 'font-awesome/css/font-awesome.min.css',
    paths.from.vendor   + 'bootstrap3-wysihtml5-bower/dist/bootstrap3-wysihtml5.min.css',
    paths.from.vendor   + 'select2/select2.css',
    paths.from.vendor   + 'select2-bootstrap-css/select2-bootstrap.min.css',
    paths.from.vendor   + 'bootstrap-fileinput/css/fileinput.min.css',
    paths.from.adminlte + 'plugins/datatables/dataTables.bootstrap.css',
    paths.from.adminlte + 'dist/css/AdminLTE.min.css',
    paths.from.css + '*'
];

// Directories
gulp.task('directories:clean', function(cb) {
    del(Object.keys(directories).map(function(key) {
        return directories[key];
    }), cb);
});

gulp.task('directories', ['directories:clean'], function() {
    for (var from in directories) {
        gulp.src([from]).pipe(gulp.dest(directories[from]));
    }
});

// CSS
gulp.task('css:clean', ['directories'], function(cb) {
    del([paths.to.css], cb);
});

gulp.task('css', ['css:clean'], function() {
    return gulp.src(css_files)
        .pipe(concat('app.min.css'))
        .pipe(minifycss({keepSpecialComments: 0, processImport: false}))
        .pipe(replace(/url\(images/g, "url(../datatables/images"))
        .pipe(replace(/url\(select2/g, "url(../select2/select2"))
        .pipe(replace(/url\(..\/img/g, "url(../bootstrap-fileinput/img"))
        .pipe(replace(/@import[^;]+;/g, ''))
        .pipe(gulp.dest(paths.to.css));
});

// JS
gulp.task('js:clean', ['directories'], function(cb) {
    del([paths.to.js], cb);
});

gulp.task('js', ['js:clean'], function() {
    return gulp.src(js_files)
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.to.js));
});

// Version
gulp.task('version', ['directories', 'css', 'js'], function() {
    gulp.src(
        [
            paths.to.css + 'app.min.css',
            paths.to.js + 'app.min.js'
        ], {base: paths.to.build})
        .pipe(gulp.dest(paths.to.build))
        .pipe(rev())
        .pipe(gulp.dest(paths.to.build))
        .pipe(rev.manifest())
        .pipe(gulp.dest(paths.to.build))
        .on('end', function() {
            del(paths.to.css + 'app.min.css');
            del(paths.to.js + 'app.min.js');
        });
});

// Launch
gulp.task('build', ['directories', 'css', 'js', 'version']);
