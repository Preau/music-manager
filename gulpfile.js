var babel = require('gulp-babel'),
	concat = require('gulp-concat'),
	gulp = require('gulp'),
	rename = require('gulp-rename'),
	uglify = require('gulp-uglify');

var paths = {
	js: {
		src: 'js/**/*.js',
		libs: [
			'node_modules/jquery/dist/jquery.min.js'
		]
	},
	output: 'build'
};

gulp.task('js-src', () => {
	return gulp.src(paths.js.src)
		.pipe(babel({presets: ['es2015']}))
		.pipe(concat('script.js'))
		.pipe(gulp.dest(paths.output))
		.pipe(uglify())
		.pipe(rename({suffix:'.min'}))
		.pipe(gulp.dest(paths.output));
});

gulp.task('js-libs', () => {
	return gulp.src(paths.js.libs)
		.pipe(concat('libraries.js'))
		.pipe(gulp.dest(paths.output));
});

gulp.task('default', ['js-src', 'js-libs']);

gulp.task('watch', () => {
	gulp.watch(paths.js.src, ['js-src']);
});