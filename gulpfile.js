var	autoprefixer = require('gulp-autoprefixer'),
	babel = require('gulp-babel'),
   	cleanCss = require('gulp-cleancss'),
	concat = require('gulp-concat'),
	gulp = require('gulp'),
	livereload = require('gulp-livereload'),
	rename = require('gulp-rename'),
	sass = require('gulp-sass'),
	uglify = require('gulp-uglify');

var paths = {
	js: {
		src: 'js/**/*.js',
		libs: [
			'node_modules/jquery/dist/jquery.min.js'
		]
	},
	css: {
		watch: 'sass/**/*.scss',
		src: 'sass/style.scss',
		libs: [
			'node_modules/font-awesome/css/font-awesome.min.css'
		]
	},
	output: 'build'
};

gulp.task('js', () => {
	return gulp.src(paths.js.src)
		.pipe(babel({presets: ['es2015']}))
		.pipe(concat('script.js'))
		.pipe(gulp.dest(paths.output))
		.pipe(uglify())
		.pipe(rename({suffix:'.min'}))
		.pipe(gulp.dest(paths.output))
		.pipe(livereload());
});

gulp.task('js-libs', () => {
	return gulp.src(paths.js.libs)
		.pipe(concat('libraries.js'))
		.pipe(gulp.dest(paths.output));
});

gulp.task('css', () => {
	return gulp.src(paths.css.src)
		.pipe(sass().on('error', sass.logError))
		.pipe(autoprefixer({browsers: ['last 2 versions', '>5%']}))
		.pipe(gulp.dest(paths.output))
		.pipe(cleanCss())
		.pipe(rename({suffix:'.min'}))
		.pipe(gulp.dest(paths.output))
		.pipe(livereload());
});

gulp.task('css-libs', () => {
	return gulp.src(paths.css.src)
		.pipe(concat('libraries.css'))
		.pipe(gulp.dest(paths.output));
});

gulp.task('default', ['js', 'js-libs', 'css']);

gulp.task('watch', () => {
	livereload.listen();
	gulp.watch(paths.js.src, ['js']);
	gulp.watch(paths.css.watch, ['css']);
});