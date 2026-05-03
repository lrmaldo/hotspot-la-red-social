<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Zona;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use ZipArchive;
use Illuminate\Support\Facades\Storage;

class MikrotikDownloadController extends Controller
{
    public function download(Zona $zona)
    {
        $loginHtml = View::make('mikrotik.login', ['zona' => $zona])->render();
        $aloginHtml = View::make('mikrotik.alogin', ['zona' => $zona])->render();
        
        $zipFile = storage_path('app/public/mikrotik_' . $zona->id_personalizado . '.zip');
        
        $zip = new ZipArchive();
        if ($zip->open($zipFile, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            $zip->addFromString('login.html', $loginHtml);
            $zip->addFromString('alogin.html', $aloginHtml);
            // Optionally, add an error.html redirecting too
            $zip->close();
        }
        
        return response()->download($zipFile)->deleteFileAfterSend(true);
    }
}
