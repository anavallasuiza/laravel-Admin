// Load plugins
var gulp = require('gulp'),
    replace = require('gulp-replace'),
    concatcss = require('gulp-concat-css'),
    minifycss = require('gulp-minify-css'),
    uglify = require('gulp-uglify'),
    concat = require('gulp-concat'),
    rev = require('gulp-rev'),
    del = require('del'),
    jshint = require('gulp-jshint'),
    stylish = require('jshint-stylish'),
    filesExist = require('files-exist');

var paths = {
    'from': {
        'css': './src/anavallasuiza/laravel-Admin/resources/assets/css/',
        'js': './src/anavallasuiza/laravel-Admin/resources/assets/js/',
        'vendor': './assets/vendor/',
        'adminlte': 'vendor/almasaeed2010/adminlte/'
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
    paths.from.vendor   + 'summernote/dist/summernote.min.js',
    paths.from.vendor   + 'select2/dist/js/select2.min.js',
    paths.from.vendor   + 'bootstrap-fileinput/js/fileinput.min.js',
    paths.from.vendor   + 'DataTables/media/js/jquery.dataTables.min.js',
    paths.from.adminlte + 'plugins/datatables/dataTables.bootstrap.js',
    paths.from.js + '*'
];

var css_files = [
    paths.from.vendor   + 'bootstrap/dist/css/bootstrap.min.css',
    paths.from.vendor   + 'font-awesome/css/font-awesome.min.css',
    paths.from.vendor   + 'summernote/dist/summernote.css',
    paths.from.vendor   + 'select2/dist/css/select2.min.css',
    paths.from.vendor   + 'select2-bootstrap-theme/dist/select2-bootstrap.min.css',
    paths.from.vendor   + 'bootstrap-fileinput/css/fileinput.min.css',
    paths.from.adminlte + 'plugins/datatables/dataTables.bootstrap.css',
    paths.from.adminlte + 'dist/css/AdminLTE.min.css',
    paths.from.adminlte + 'dist/css/skins/skin-blue.min.css',
    paths.from.css + '*'
];

// Directories
gulp.task('directories:clean', function(cb) {
    return del(Object.keys(directories).map(function(key) {
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
    return del([paths.to.css], cb);
});

gulp.task('css', ['css:clean'], function() {
    return gulp.src(filesExist(css_files))
        .pipe(concatcss('app.min.css'))
        .pipe(minifycss({
            keepSpecialComments: 0,
            restructuring: false,
            processImport: false
        }))
        .pipe(replace(/url\(images/g, "url(../datatables/images"))
        .pipe(replace(/url\(..\/img/g, "url(../bootstrap-fileinput/img"))
        .pipe(replace(/(\.\.\/)+[^\/]+\/fonts/g, '../fonts'))
        .pipe(replace(/@import[^;]+;/g, ''))
        .pipe(gulp.dest(paths.to.css));
});

// JS
gulp.task('js:clean', ['directories'], function(cb) {
    return del([paths.to.js], cb);
});

gulp.task('js:lint', function() {
    var files = css_files.filter(function(file) {
        return file.indexOf('vendor') === -1;
    });

    return gulp
        .src(files)
        .pipe(jshint())
        .pipe(jshint.reporter(stylish));
});

gulp.task('js', ['js:clean'], function() {
    return gulp.src(filesExist(js_files))
        .pipe(concat('app.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest(paths.to.js));
});

// Version
gulp.task('version', ['directories', 'css', 'js'], function() {
    return gulp.src(
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
gulp.task('default', ['directories', 'css', 'js', 'version']);
