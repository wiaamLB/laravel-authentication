<?php

use Illuminate\Support\Facades\Storage;

if (!function_exists('copy_file_storage_s3_admin')) {
    function copy_file_storage_s3_admin($item, $location)
    {
        $date = new DateTime();
        $rand = $date->getTimestamp() . '-' . rand(1, 1000);
        $explode = explode('/', $item);
        $image_location = 'storage/Admin/' . $location . '/' . $rand . '_';
        Storage::copy(
            $item,
            str_replace('tmp/', $image_location, $item)
        );
        $image_location = 'storage/Admin/' . $location . '/' . $rand . '_' . $explode[sizeof($explode) - 1];

        return $image_location;
    }

}
