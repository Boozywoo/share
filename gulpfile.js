const elixir = require('secret-elixir');

let
    resources = './resources/assets/',
    public = './public/assets/',
    bower = './bower_components/';

elixir(mix => {
    mix
        //Admin
        .less([
            `${resources}admin/less/*.less`,
            `${resources}admin/less/mainStyles.css`,
            `${resources}admin/less/forBusScale.css`
        ]
            , `${public}admin/css/styles.css`)
        //
        .scripts([
            `${bower}jquery-pjax/jquery.pjax.js`,
            `${bower}jquery.scrollTo/jquery.scrollTo.min.js`,
            `${resources}admin/js/markup/locationOfSeats.js`
        ], `${public}admin/js/libs.js`)
        //
        // .copy(`${resources}admin/images`, `${public}admin/images`)
        //
        .browserify(`${resources}admin/js/index.js`, `${public}admin/js/scripts.js`)

        //Driver
        .less(`${resources}driver/css/*.css`, `${public}driver/css/styles.css`)
        .scripts(`${resources}driver/js/*.js`, `${public}driver/js/scripts.js`)

        // Index
        .less([
            `${resources}index/css/plugins/froala.css`,
            `${resources}index/plugins/datepicker3.css`,
            `${resources}index/css/main.css`,
            `${bower}toastr/toastr.less`
        ], `${public}index/css/style.css`)
        // //
        .scripts([
            `${bower}jquery-form/src/jquery.form.js`,
            `${resources}index/plugins/bootstrap-datepicker.js`,
            `${resources}index/js/app/map.js`,
            `${bower}jquery.inputmask/dist/min/jquery.inputmask.bundle.min.js`,
            `${bower}toastr/toastr.min.js`,
            `${bower}select2/dist/js/select2.js`
        ], `${public}index/js/main.js`)
        //
        // .copy(`${resources}index/fonts`, `${public}index/fonts`)
        // .copy(`${resources}index/js`, `${public}index/js`)
        .copy(`${resources}index/css`, `${public}index/css`)
        // .copy(`${resources}index/images/**/*.*`, `${public}index/images`)

        .browserify(`${resources}index/js/app/app.js`, `${public}index/js/app.js`)

});
