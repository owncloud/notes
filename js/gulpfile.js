var gulp = require('gulp');

/**
 * Configuration
 */
var jsHintRc = '.jshintrc';
var karmaConfig = __dirname + '/karma.conf.js';  // karma needs absolute path
var phpunitBinary = 'phpunit';
var phpunitConfig = '../phpunit.xml';
var phpunitIntegrationConfig = '../phpunit.integration.xml';
var buildFolder = 'public';
var buildTarget = 'app.min.js';

var sources = {
    css: ['../css/**/*.css'],
    ts: ['config/app.ts', 'app/**/*.ts'],
    tests: ['tests/**/*.js'],
    php: ['../**/*.php'],
    config: ['karma.conf.js', 'gulpfile.js']
};

/**
 * Task definitions
 */
gulp.task('default', ['build']);

gulp.task('build', function () {
    'use strict';
    var uglify = require('gulp-uglify'),
        sourcemaps = require('gulp-sourcemaps'),
        tslint = require('gulp-tslint'),
        ngAnnotate = require('gulp-ng-annotate'),
        tsc = require('gulp-typescript');

    return gulp.src(sources.ts)
        .pipe(tslint({
            configuration: {
                rules: {
                    semicolon: true,
                    //requireReturnType: true,
                    //requireParameterType: true
                }
            }
        }))
        .pipe(tslint.report('prose', {emitError: true}))
        .pipe(sourcemaps.init())
            .pipe(tsc({
                //noImplicitAny: true,
                typescript: require('typescript'),
                target: 'ES5',
                module: 'commonjs',
                out: buildTarget
            }))
            .pipe(ngAnnotate())
            //.pipe(uglify())
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(buildFolder));
});

gulp.task('clean', function () {
    'use strict';
    var del = require('del');
    del(buildFolder);
});


gulp.task('test-all', ['test', 'test-php', 'test-php-integration']);

gulp.task('test', function (done) {
    'use strict';
    var karma = require('karma');

    new karma.Server({
        configFile: karmaConfig,
        singleRun: true
    }, done).start();
});

gulp.task('test-php', function () {
    'use strict';
    var phpunit = require('gulp-phpunit');

    gulp.src(phpunitConfig)
        .pipe(phpunit(phpunitBinary, {silent: true}));
});

gulp.task('test-php-integration', function () {
    'use strict';
    var phpunit = require('gulp-phpunit');

    gulp.src(phpunitIntegrationConfig)
        .pipe(phpunit(phpunitBinary, {silent: true}));
});


// watch tasks
gulp.task('watch', ['default'], function () {
    'use strict';
    gulp.watch(sources.ts
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
