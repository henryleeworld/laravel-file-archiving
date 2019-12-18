<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use ZipArchive;
use RuntimeException;

class ZipController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function downloadFile()
    {
        $zip = new ZipArchive;
        $fileName = 'archive.zip';
		$filePath = public_path($fileName);
		
		if (file_exists($filePath)) {
            unlink($filePath);
        }
		
        if (($zipStatus = $zip->open($filePath, ZipArchive::CREATE)) !== TRUE) {
            throw new RuntimeException(sprintf('Failed to create zip archive. (Status code: %s)', $zipStatus));
        }

        $files = File::files(public_path('uploads'));
        foreach ($files as $key => $value) {
            if (!$zip->addFile($value, basename($value))) {
                throw new RuntimeException(sprintf('Add file failed: %s', $value));
            }
        }
        $zip->close();
        return response()->download($filePath);
    }
}
