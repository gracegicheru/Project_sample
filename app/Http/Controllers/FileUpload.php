<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\FileRepository;
use App\Upload;

class FileUpload extends Controller
{
    //

    public function getForm(){
        return view('upload');
    }
    public function StoreFile(){
        $file = \request()->file('file');
        $moved = FileRepository::move($file,true);
//        dd($moved);
        $data['file'] = $moved['path'];

        $upload= new Upload;

        $upload->file_extension= $data['file'];
        $upload->save();


    }
}
