<?php
//Helper hỗ trợ upload ảnh sản phẩm
namespace App\Helpers;

class FileUploader
{
    // Upload 1 file ảnh (Ví dụ: Thumbnail)
    public static function uploadSingle($file, $targetDir = 'uploads/products/')
    {
        if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadPath = __DIR__ . '/../../public/' . $targetDir;
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $allowedExtensions = ['jpg', 'jpeg', 'png', 'webp'];

        if (!in_array(strtolower($extension), $allowedExtensions)) {
            return null;
        }

        // Tạo tên file duy nhất bằng timestamp + random string
        $filename = time() . '_' . uniqid() . '.' . $extension;
        $destination = $uploadPath . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return $targetDir . $filename;
        }

        return null;
    }
}