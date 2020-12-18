/* eslint-disable @typescript-eslint/no-var-requires */
const composer = require('gulp-composer');
const del = require('del');
const gulp = require('gulp');
const typescript = require('gulp-typescript');
const zip = require('gulp-zip');

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
	return del(['js/**']);
}

function compress() {
	return gulp
		.src(['inc/**', 'js/**', 'vendor/**', 'email-pdf-card.php'], {
			base: '../',
		})
		.pipe(zip('email-pdf-card.zip'))
		.pipe(gulp.dest('./'));
}

function watch() {
	gulp.watch(['./ts/*.ts'], scripts);
}

exports.clean = clean;
exports.compress = compress;
exports.phpComposer = phpComposer;
exports.scripts = scripts;
exports.watch = watch;

exports.build = gulp.series(clean, phpComposer, scripts);
exports.default = gulp.series(phpComposer, scripts, watch);
exports.zip = gulp.series(clean, phpComposer, scripts, compress);
