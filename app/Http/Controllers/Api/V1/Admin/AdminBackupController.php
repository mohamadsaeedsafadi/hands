<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;

class AdminBackupController extends Controller
{
    public function index()
    {
        $files = File::files(
            storage_path('app/backups')
        );

        return response()->json(
            collect($files)->map(function ($file) {

                return [

                    'name' =>
                        $file->getFilename(),

                    'size' =>
                        round(
                            $file->getSize() / 1024,
                            2
                        ) . ' KB',

                    'date' =>
                        date(
                            'Y-m-d H:i:s',
                            $file->getMTime()
                        ),
                ];
            })
        );
    }

    public function download($file)
    {
        $path =
            storage_path(
                'app/backups/' . $file
            );

        return response()->download($path);
    }
}