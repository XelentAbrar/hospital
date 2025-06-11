<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait File
{
    public function uploadDocument($images, $prefix, $path, $name, $private){
        // return $path;
        $storagePath = $private == 'true' ? 'private' : 'public';

        if ($private) {
           // return 'private 1';
            if (!file_exists(storage_path($storagePath . '/' . $path))) {
                mkdir(storage_path($storagePath . '/' . $path), 0777, true);
            }
        }

        if (!$private) {
          //  return 'public 0';
            if (!file_exists(public_path('uploads/Inventory'))) {
                mkdir(public_path('uploads/Inventory'), 0777, true);
            }

            if (!file_exists(public_path('uploads/HRMS'))) {
                mkdir(public_path('uploads/HRMS'), 0777, true);
            }

        }

        if ($images->hasFile($name)) {
            $file = $images->file($name);
            $originalName = $file->getClientOriginalName();
            $randomChars = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
            $uniqueFilename = $prefix . $randomChars . '-' . $originalName;


            $disk = $private ? 'local' : 'public';
            $storagePath = $private ? $storagePath . '/' . $path : $path;

            Storage::disk($disk)->putFileAs($storagePath, $file, $uniqueFilename);

            return response()->json([$name => $uniqueFilename]);
        }
        return '';
    }


    public function uploadDocument1($images, $prefix, $path, $name){

        if (!file_exists(storage_path('app/public/'.$path))) {
            mkdir(storage_path('app/public/'.$path));
        }


        if (!file_exists(public_path('uploads/Inventory'))) {
            mkdir(public_path('uploads/Inventory'));
        }

        if (!file_exists(public_path('uploads/HRMS'))) {
            mkdir(public_path('uploads/HRMS'));
        }

        if ($images->hasFile($name)) {
            $file = $images->file($name);
            $originalName = $file->getClientOriginalName();
            $randomChars = substr(str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
            $uniqueFilename = $prefix . $randomChars . '-' . $originalName;
            Storage::disk('public')->putFileAs($path, $file, $uniqueFilename);
            return response()->json([$name => $uniqueFilename]);
        }
        return '';
    }
}
