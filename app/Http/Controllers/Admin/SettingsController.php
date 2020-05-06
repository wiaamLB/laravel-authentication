<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        return response(['success' => true, 'data' => Setting::first()]);
    }

    public function store()
    {

        $data = request()->validate([
            'social_media' => [],
            'contact_phone' => [],
            'contact_email' => [],
            'others' => [],
        ]);

        $settings = Setting::updateOrCreate(['id' => 1], $data);
        return response(['success' => true, 'data' => $settings]);
    }

}
