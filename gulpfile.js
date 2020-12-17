/* eslint-disable @typescript-eslint/no-var-requires */
const typescript = require('gulp-typescript');
const gulp = require('gulp');
const composer = require('gulp-composer');
const del = require('del');

const ENV_PROD = process.env.NODE_ENV === 'production' ? true : false;

function scripts() {
	return gulp
		.src(['./ts/email-pdf-admin.ts'])
		.pipe(typescript())
		.pipe(gulp.dest('./js/'));
}

function phpComposer() {
	if (ENV_PROD) {
		composer('install --no-dev', { async: false });
	} else {
		composer('install', { async: true });
	}
	return composer('dump-autoload -o', { async: false });
}

function clean() {
	return del(['del/']);
}

function watch() {
	gulp.watch(['./ts/*.ts'], scripts);
}

exports.clean = clean;
exports.phpComposer = phpComposer;
exports.scripts = scripts;
exports.watch = watch;

exports.build = gulp.series(phpComposer, scripts);
exports.default = gulp.series(phpComposer, scripts, watch);
