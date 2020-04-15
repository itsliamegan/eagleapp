import dotenv from 'dotenv';
import { src, dest, series, watch } from 'gulp';
import del from 'del';
import sourcemaps from 'gulp-sourcemaps';
import rollup from 'gulp-better-rollup';
import babel from 'rollup-plugin-babel';
import resolve from 'rollup-plugin-node-resolve';
import commonjs from 'rollup-plugin-commonjs';
import replace from 'rollup-plugin-replace';
import terser from 'gulp-terser';
import rev from 'gulp-rev';
import sass from 'gulp-sass';
import sassCompiler from 'node-sass';
import postcss from 'gulp-postcss';
import cssnano from 'cssnano';
import imagemin from 'gulp-imagemin';

sass.compiler = sassCompiler;
dotenv.config();

const isDevelopment = process.env.NODE_ENV === 'development';

function clean() {
  return del(['assets.json', 'public/assets/js', 'public/assets/css']);
}

function bundle() {
  return rollup(
    {
      plugins: [
        babel({
          exclude: 'node_modules/**',
        }),
        resolve({
          browser: true,
        }),
        commonjs(),
        replace({
          ENV: JSON.stringify(process.env.NODE_ENV),
          GOOGLE_CLIENT_ID: JSON.stringify(process.env.GOOGLE_CLIENT_ID),
        }),
      ],
      onwarn: () => {},
    },
    {
      format: 'umd',
      sourcemaps: isDevelopment && 'inline',
    }
  );
}

function jsDev() {
  return src('resources/js/app.js')
    .pipe(sourcemaps.init())
    .pipe(bundle())
    .pipe(rev())
    .pipe(sourcemaps.write())
    .pipe(dest('public/assets/js'))
    .pipe(rev.manifest('assets.json', { merge: true }))
    .pipe(dest('.'));
}

function js() {
  return src('resources/js/app.js')
    .pipe(bundle())
    .pipe(terser())
    .pipe(rev())
    .pipe(dest('public/assets/js'))
    .pipe(rev.manifest('assets.json', { merge: true }))
    .pipe(dest('.'));
}

function cssDev() {
  return src('resources/scss/**/*.scss')
    .pipe(sourcemaps.init())
    .pipe(sass().on('error', sass.logError))
    .pipe(rev())
    .pipe(sourcemaps.write())
    .pipe(dest('./public/assets/css'))
    .pipe(rev.manifest('assets.json', { merge: true }))
    .pipe(dest('.'));
}

function css() {
  return src('resources/scss/**/*.scss')
    .pipe(sass().on('error', sass.logError))
    .pipe(postcss([cssnano()]))
    .pipe(rev())
    .pipe(dest('./public/assets/css'))
    .pipe(rev.manifest('assets.json', { merge: true }))
    .pipe(dest('.'));
}

function img() {
  return src('resources/img/**/*.{png,jpg,jpeg,svg}')
    .pipe(imagemin())
    .pipe(rev())
    .pipe(dest('./public/assets/img'))
    .pipe(rev.manifest('assets.json', { merge: true }))
    .pipe(dest('.'));
}

export const buildDev = series(clean, jsDev, cssDev, img);

export function buildWatch() {
  watch('resources/**/*.{js,scss,png,jpg,jpeg,svg}', buildDev);
}

export const build = series(clean, js, css, img);
