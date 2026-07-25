<?php

namespace App\Controllers\Admin;

use App\Controllers\Controller;
use App\Models\Setting;
use App\Helpers\FileUploader;
use App\Helpers\CsrfHelper;

class SettingController extends Controller
{
    public function index()
    {
        $settingsRaw = Setting::all();
        $settings = [];
        foreach ($settingsRaw as $item) {
            $settings[$item->key_name] = $item->key_value;
        }

        $this->view('admin/settings/index', ['settings' => $settings], 'admin');
    }

    public function update()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') return;
        
        CsrfHelper::validate();

        $data = $_POST;
        $files = $_FILES;

        $textSettings = [
            'site_name',
            'site_hotline',
            'site_email',
            'primary_color',
            'announcement_text',
            'maintenance_mode'
        ];

        foreach ($textSettings as $key) {
            $value = $data[$key] ?? '';
            if ($key === 'maintenance_mode') {
                $value = isset($data['maintenance_mode']) ? '1' : '0';
            }

            Setting::updateOrCreate(['key_name' => $key], ['key_value' => $value]);
        }

        if (isset($files['site_logo']) && $files['site_logo']['error'] === UPLOAD_ERR_OK) {
            $logoPath = FileUploader::uploadSingle($files['site_logo'], 'uploads/settings/');
            if ($logoPath) {
                Setting::updateOrCreate(['key_name' => 'site_logo'], ['key_value' => $logoPath]);
            }
        }

        $this->redirect('/admin/settings?success=1');
    }
}