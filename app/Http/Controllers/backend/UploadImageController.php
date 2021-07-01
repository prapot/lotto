<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Storage;

class UploadImageController extends Controller
{
    public function upload(Request $request){
      $file = $request->file('file');
      $file_name = time() . '-' . $file->getClientOriginalName();
      Storage::disk('public')->putFileAs('note/file', $file , $file_name);
      echo url('/storage/note/file/'.$file_name);
    }
    public function uploadTemp(Request $request){
      $file = $request->file($request->title);
      $file_name = time() . '-' . $file->getClientOriginalName();
      Storage::disk('public')->putFileAs('image/', $file , $file_name);
      return $file_name;
    }
    public function removeTemp(Request $request){
      $file = $request->file('file');
      dd($request->all());
      $file_name = time() . '-' . $file->getClientOriginalName();
      Storage::disk('public')->delete('temp/', $file , $file_name);
      return $file_name;
    }
    
}
