<?php

namespace App\Http\Controllers;

use File;
use Illuminate\Http\Request;
use RuntimeException;
use ZipArchive;

class ZipController extends Controller
{
    public function downloadFile()
    {
        $zip = new ZipArchive;
        $fileName = 'archive.zip';
		$filePath = storage_path($fileName);
		
		if (file_exists($filePath)) {
            unlink($filePath);
        }
		
        if (($zipStatus = $zip->open($filePath, ZipArchive::CREATE)) !== TRUE) {
            throw new RuntimeException(sprintf('Failed to create zip archive. (Status code: %s)', $zipStatus));
        }

        $files = File::files(storage_path('uploads'));
        foreach ($files as $key => $value) {
            if (!$zip->addFile($value, basename($value))) {
                throw new RuntimeException(sprintf('Add file failed: %s', $value));
            }
        }
        $zip->close();
        return response()->download($filePath);
    }
}
