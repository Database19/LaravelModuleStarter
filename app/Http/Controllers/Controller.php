<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\File;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function createFileInModule(string $moduleName, string $relativePath, string $content): void
    {
        // Path ke folder resources dalam modul
        $basePath = base_path("Modules/{$moduleName}/resources/");

        // Path lengkap ke file
        $filePath = $basePath . $relativePath;

        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Pastikan direktori tujuan ada, jika tidak, buat direktori
        $directory = dirname($filePath);
        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        // Buat file dengan konten
        file_put_contents($filePath, $content);

        echo "File created at: $filePath\n";
    }
}
