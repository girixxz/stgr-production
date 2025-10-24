<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cloudinary Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for configuring your Cloudinary account.
    |
    | Here you may configure your Cloudinary configuration.
    |
    */

    'cloud_url' => env('CLOUDINARY_URL'),

    /**
     * Upload Preset From Cloudinary Dashboard
     */
    'upload_preset' => env('CLOUDINARY_UPLOAD_PRESET'),

    /**
     * Cloudinary Notification URL
     */
    'notification_url' => env('CLOUDINARY_NOTIFICATION_URL'),
];
