var gulp = require('gulp');

/**
 * Configuration
 */
var jsHintRc = '.jshintrc';
var karmaConfig = __dirname + '/karma.conf.js';  // karma needs absolute path
var buildFolder = 'public';
var buildTarget = 'app.min.js';

var sources = {
    css: ['../css/**/*.css'],
    js: ['config/app.js', 'app/**/*.js'],
    tests: ['tests/**/*.js'],
    php: ['../**/*.php'],
    config: ['karma.conf.js', 'gulpfile.js']
};

var wrappers = '(function(angular, $, requestToken, mdEdit, undefined){'+
    '\'use strict\';<%= contents %>' +
    '})(angular, jQuery, oc_requesttoken, mdEdit);';


/**
 * Task definitions
 */
gulp.task('lint', function () {
    'use strict';
    var jshint = require('gulp-jshint');

    return gulp.src(sources.js
            .concat(sources.tests)
            .concat(sources.config))
        .pipe(jshint(jsHintRc))
        .pipe(jshint.reporter('jshint-stylish'));
});

gulp.task('build', function () {
    'use strict';
    var ngAnnotate = require('gulp-ng-annotate'),
        wrap = require('gulp-wrap'),
        uglify = require('gulp-uglify'),
        sourcemaps = require('gulp-sourcemaps'),
        concat = require('gulp-concat');

    return gulp.src(sources.js)
        .pipe(sourcemaps.init())
            .pipe(concat(buildTarget))
            .pipe(ngAnnotate())
            .pipe(wrap(wrappers))
            .pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(buildFolder));
});

gulp.task('default', gulp.series('lint', 'build'));

gulp.task('clean', function () {
    'use strict';
    var del = require('del');
    del(buildFolder);
});


gulp.task('test', function (done) {
    'use strict';
    var karma = require('karma');

    new karma.Server({
        configFile: karmaConfig,
        singleRun: true
    }, done).start();
});

// watch tasks
gulp.task('watch', gulp.series('default'), function () {
    'use strict';
    gulp.watch(sources.js
        .concat(sources.tests)
        .concat(sources.css)
        .concat(sources.config), ['default']);
});

gulp.task('watch-test', function (done) {
    'use strict';
    var karma = require('karma');

    new karma.Server({
        configFile: karmaConfig
    }, done).start();
});

gulp.task('watch-test-php', function () {
    'use strict';
    gulp.watch(sources.php, ['test-php']);
});
