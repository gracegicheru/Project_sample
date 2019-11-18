<?php

namespace App\Http\Controllers;

use App\Upload;
use Illuminate\Http\Request;

class ImagesController extends Controller
{
    public function Images(){

        $upload= Upload::all();
//        dd($upload);
        return view('images',['uploads' => $upload]);

    }

}
