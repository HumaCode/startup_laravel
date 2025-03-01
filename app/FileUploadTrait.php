<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

trait FileUploadTrait
{
    function uploadImage(Request $request, $inputName, $oldPath = null, $path, $compressionLevel = 80)
    {
        // Jika compressionLevel yang diterima adalah 0, set ke 80
        if ($compressionLevel === 0) {
            $compressionLevel = 80;
        }


        if ($request->hasFile($inputName)) {
            $image = $request->file($inputName);
            $ext = $image->getClientOriginalExtension();

            // Validasi ekstensi
            $allowedExtensions = ['png', 'jpg', 'jpeg'];
            if (!in_array(strtolower($ext), $allowedExtensions)) {
                return null; // Return null jika format tidak valid
            }

            // $imageName = $inputName . '_' . uniqid() . '.' . $ext;
            $imageName = $inputName . '_' . date('Y_m_d_H_i_s') . '.' . $ext;

            // Buat direktori tujuan jika belum ada
            $destinationPath = storage_path('app/public/' . $path);
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0755, true);
            }

            // Baca gambar dan kompres
            $imageResource = null;
            $compressedPath = $destinationPath . '/' . $imageName;

            switch (strtolower($ext)) {
                case 'jpeg':
                case 'jpg':
                    $imageResource = imagecreatefromjpeg($image->getRealPath());
                    $compressionQuality = max(0, min(100, $compressionLevel)); // Pastikan dalam rentang 0-100
                    imagejpeg($imageResource, $compressedPath, $compressionQuality);
                    break;

                case 'png':
                    $imageResource = imagecreatefrompng($image->getRealPath());
                    $compressionQuality = max(0, min(9, round($compressionLevel / 10))); // Skala kompresi 0-9
                    imagepng($imageResource, $compressedPath, $compressionQuality);
                    break;
            }

            // Hapus resource gambar dari memori
            if ($imageResource) {
                imagedestroy($imageResource);
            }

            // Hapus gambar lama jika ada
            $excludedFolder = 'default';
            $fullOldPath = storage_path('app/public/' . $oldPath);
            if ($oldPath && File::exists($fullOldPath) && !str_contains($oldPath, $excludedFolder)) {
                File::delete($fullOldPath);
            }

            // Return path baru tanpa 'public/'
            return $path . '/' . $imageName;
        }

        return null; // Return null jika tidak ada file yang diunggah
    }
}
