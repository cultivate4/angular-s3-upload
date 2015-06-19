var gulp = require('gulp');
var uglify = require('gulp-uglify');
var sourcemaps = require('gulp-sourcemaps');
var rename = require('gulp-rename');

var paths = {
    scripts: './src/*.js',
    distribution: './dist'
};

gulp.task('scripts', function() {
    return gulp.src(paths.scripts)
        .pipe(sourcemaps.init())
            .pipe(uglify())
            .pipe(rename('angular-s3-upload.min.js'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest(paths.distribution));
});

gulp.task('default', ['scripts']);