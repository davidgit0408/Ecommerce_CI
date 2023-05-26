const { src, dest } = require('gulp');
const concat = require('gulp-concat');
const terser = require('gulp-terser');
const sourcemaps = require('gulp-sourcemaps');
const postcss = require('gulp-postcss');
const cssnano = require('cssnano');
const autoprefixer = require('autoprefixer');

const minify = require('gulp-clean-css');
const minifyJs = require('gulp-uglify');

const cssPath = 'assets/front_end/classic/css/*.css';

function cssBundle() {
    return src([
        'assets/front_end/classic/css/iziModal.min.css',
        'assets/front_end/classic/css/intlTelInput.css',
        'assets/front_end/classic/css/all.min.css',
        'assets/front_end/classic/css/swiper-bundle.min.css',
        'assets/front_end/classic/css/bootstrap-tabs-x.min.css',
        'assets/front_end/classic/css/sweetalert2.min.css',
        'assets/front_end/classic/css/select2.min.css',
        'assets/front_end/classic/css/select2-bootstrap4.min.css',
        'assets/front_end/classic/css/star-rating.min.css',
        'assets/front_end/classic/css/theme.css',
        'assets/front_end/classic/css/daterangepicker.css',
        'assets/front_end/classic/css/bootstrap-table.min.css',
        'assets/front_end/classic/css/lightbox.css',
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('eshop-bundle.css'))
        .pipe(postcss([autoprefixer(), cssnano()])) //not all plugins work with postcss only the ones mentioned in their documentation
        .pipe(sourcemaps.write('.'))
        .pipe(dest('assets/front_end/classic/css'));
}
exports.cssBundle = cssBundle;

function cssBundleMain() {
    return src([
        'assets/front_end/classic/css/bootstrap.min.css',
        'assets/front_end/classic/css/style.css',
        'assets/front_end/classic/css/products.css',
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('eshop-bundle-main.css'))
        .pipe(postcss([autoprefixer(), cssnano()])) //not all plugins work with postcss only the ones mentioned in their documentation
        .pipe(sourcemaps.write('.'))
        .pipe(dest('assets/front_end/classic/css'));
}
exports.cssBundleMain = cssBundleMain;

function cssBundleMainRTL() {
    return src([
        'assets/front_end/classic/css/rtl/bootstrap.min.css',
        'assets/front_end/classic/css/rtl/style.css',
        'assets/front_end/classic/css/rtl/products.css',
    ])
        .pipe(sourcemaps.init())
        .pipe(concat('eshop-bundle-main.css'))
        .pipe(postcss([autoprefixer(), cssnano()])) //not all plugins work with postcss only the ones mentioned in their documentation
        .pipe(sourcemaps.write('.'))
        .pipe(dest('assets/front_end/classic/css/rtl'));
}
exports.cssBundleMainRTL = cssBundleMainRTL;


// minifying js
const jsBundle = () =>
    src([
        'assets/front_end/classic/js/iziModal.min.js',
        'assets/front_end/classic/js/popper.min.js',
        'assets/front_end/classic/js/bootstrap.min.js',
        'assets/front_end/classic/js/swiper-bundle.min.js',
        'assets/front_end/classic/js/select2.full.min.js',
        'assets/front_end/classic/js/bootstrap-tabs-x.min.js',
        'assets/front_end/classic/js/jquery.ez-plus.js',
        'assets/front_end/classic/js/bootstrap-table.min.js',
        'assets/front_end/classic/js/jquery.blockUI.js',
        'assets/front_end/classic/js/sweetalert2.min.js',
        'assets/front_end/classic/js/modernizr-custom.js',
        'assets/front_end/classic/js/lazyload.min.js',
        'assets/front_end/classic/js/intlTelInput.js',
        'assets/front_end/classic/js/lightbox.js',
        'assets/front_end/classic/js/custom.js',
    ])
        .pipe(concat('eshop-bundle-js.js'))
        .pipe(minifyJs())
        .pipe(dest('assets/front_end/classic/js/'));

exports.jsBundle = jsBundle;

const topJsBundle = () =>
    src([
        'assets/front_end/classic/js/moment.min.js',
        'assets/front_end/classic/js/daterangepicker.js',
        'assets/front_end/classic/js/star-rating.js',
        'assets/front_end/classic/js/theme.min.js',
    ])
        .pipe(concat('eshop-bundle-top-js.js'))
        .pipe(minifyJs())
        .pipe(dest('assets/front_end/classic/js/'));

exports.topJsBundle = topJsBundle;




