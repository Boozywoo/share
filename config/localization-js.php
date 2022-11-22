<?php


return [

    /*
     * Set the names of files you want to add to generated javascript.
     * Otherwise all the files will be included.
     *
     * 'messages' => [
     *     'validation',
     *     'forum/thread',
     * ],
     */
    'messages' => [
//        'admin',
//        'admin_labels',
//        'messages',
//        'pretty',
//        'validation'
    ],

    /*
     * The default path to use for the generated javascript.
     */
//    'path' => public_path('assets/lang.js'),
    'path' => resource_path('assets/vue/dictionary.js'),

    // php artisan lang:js --no-lib
];