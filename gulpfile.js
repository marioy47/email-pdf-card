const typescript = require('gulp-typescript');
const gulp = require('gulp');

function scripts() {
	return gulp
		.src(['./ts/index.ts'])
		.pipe(typescript())
		.pipe(gulp.dest('./js/'));
}

exports.scripts = scripts;
