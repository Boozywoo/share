let mix = require('laravel-mix');
const {vue} = require("laravel-mix");

mix.less('resources/assets/admin/less/styles.less', 'public/assets/admin/css/styles.css')
    .css('resources/assets/admin/less/mainStyles.css', 'public/assets/admin/css/styles.css')
    .css('resources/assets/admin/less/forBusScale.css', 'public/assets/admin/css/styles.css')
    //
    .scripts([
        `node_modules/jquery-pjax/jquery.pjax.js`,
        `node_modules/jquery.scrollto/jquery.scrollTo.min.js`,
        `resources/assets/admin/js/markup/locationOfSeats.js`
    ], `public/assets/admin/js/libs.js`)


    .js(`resources/assets/admin/js/index.js`, `public/assets/admin/js/scripts.js`)

    //Driver
    .css(`resources/assets/driver/css/main.css`, `public/assets/driver/css/styles.css`)

    // Index
    .css(`resources/assets/index/css/plugins/froala.css`, `public/assets/index/css/style.css`)
    .css(`resources/assets/index/plugins/datepicker3.css`, `public/assets/index/css/style.css`)
    .css(`resources/assets/index/css/main.css`, `public/assets/index/css/style.css`)
    .sass('resources/assets/index/css/bootstrap.scss', 'public/assets/index/css/bootstrap.css')
    .less(`node_modules/toastr/toastr.less`, `public/assets/index/css/style.css`)

    .scripts([
        `node_modules/jquery-form/src/jquery.form.js`,
        `resources/assets/index/plugins/bootstrap-datepicker.js`,
        `resources/assets/index/js/app/map.js`,
        `node_modules/jquery.inputmask/dist/jquery.inputmask.bundle.js`,
        `node_modules/toastr/toastr.js`,
        `node_modules/select2/dist/js/select2.js`
    ], `public/assets/index/js/main.js`)
    .copy(`resources/assets/index/css`, `public/assets/index/css`)
    .js(`resources/assets/index/js/app/app.js`, `public/assets/index/js/app.js`)
    .js('resources/assets/vue/vue.js', 'public/assets/vue.js').vue();

const WebpackShellPlugin = require('webpack-shell-plugin');

// Add shell command plugin configured to create JavaScript language file
/*mix.webpackConfig({
    plugins:
        [
            new WebpackShellPlugin({onBuildStart: ['php artisan lang:js --quiet'], onBuildEnd: []})
        ]
});*/
// .copy(`${resources}index/fonts`, `${public}index/fonts`)
// .copy(`${resources}index/js`, `${public}index/js`)
// .copy(`${resources}index/images/!**!/!*.*`, `${public}index/images`)


