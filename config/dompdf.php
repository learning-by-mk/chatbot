<?php

use Dompdf\Dompdf;

return [
    /*
    |--------------------------------------------------------------------------
    | Settings
    |--------------------------------------------------------------------------
    |
    | Set some default values. It is possible to add all defines that can be set
    | in dompdf_config.inc.php. You can also override the entire config file.
    |
    */
    'show_warnings' => false,
    'orientation' => 'portrait',
    'defines' => [
        /**
         * The font directory
         */
        'DOMPDF_FONT_DIR' => public_path('fonts/'),

        /**
         * Font cache directory
         */
        'DOMPDF_FONT_CACHE' => storage_path('fonts/'),

        /**
         * The temp directory
         */
        'DOMPDF_TEMP_DIR' => sys_get_temp_dir(),

        /**
         * Enable font subsetting
         */
        'DOMPDF_ENABLE_FONT_SUBSETTING' => true,

        /**
         * Enable remote file access
         */
        'DOMPDF_ENABLE_REMOTE' => true,

        /**
         * Use the more-than-experimental HTML5 Lib parser
         */
        'DOMPDF_ENABLE_HTML5PARSER' => true,

        /**
         * Enables the autoloader in dompdf
         */
        'DOMPDF_ENABLE_AUTOLOAD' => false,

        /**
         * Enable Unicode support
         */
        'DOMPDF_UNICODE_ENABLED' => true,

        /**
         * Enable PHP support
         */
        'DOMPDF_ENABLE_PHP' => false,

        /**
         * Enable CSS float support
         */
        'DOMPDF_ENABLE_CSS_FLOAT' => true,

        /**
         * Enable debugging
         */
        'DOMPDF_ENABLE_DEBUG' => false,

        'DOMPDF_LOG_OUTPUT_FILE' => storage_path('logs/dompdf.log'),
    ],
];
