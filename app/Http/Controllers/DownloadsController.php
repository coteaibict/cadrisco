<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadsController extends Controller
{
    public function ordinance($file)
    {

        if (! file_exists(env('APP_PATH')."storage/app/ordinance/".$file)) {
            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('ordinance/'.$file);
    }

    public function declaration($file)
    {
        if (! file_exists(env('APP_PATH')."storage/app/declaration/".$file)) {
            return redirect()
                ->back()
                ->with('error', 'Arquivo não existe!')
                ->withInput();
        }

        return Storage::download('declaration/'.$file);
    }
}
