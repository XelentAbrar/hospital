<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\File;
class AppController extends Controller
{
    use File;
    public function uploadFiles(Request $request)
    {
       return $this->uploadDocument($request, $request->prefix, $request->path, $request->name, $request->private);
        // return $this->uploadDocument($request, $request->prefix, $request->path, $request->name);
    }
}
