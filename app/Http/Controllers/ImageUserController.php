<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DB;
use Illuminate\Support\Facades\Log;


class ImageUserController extends Controller
{
    public function imageUploadAWS(){
        ini_set('max_execution_time', 18000); //300 minutes

        $files = DB::table('property_images')->select('image_url')->get();
        $fileChunkArray = $files->chunk(100);
        $fileChunkArray1 = $fileChunkArray->chunk(5);
        // dd("Image Uploading");
        foreach ($fileChunkArray1 as $k=> $fileChunks) {
            foreach($fileChunks as $key => $fileChunk){
                foreach ($fileChunk as $file) {
                    $url = $file->image_url;
                    $filename = substr(strrchr(rtrim($url, '/'), '/'), 1);
                    $data = Storage::disk('s3')->put('uploads/'.$filename, file_get_contents($url));
                    // $data = array(
                    //     'image_url' => "https://fabvenues.s3.us-east-1.amazonaws.com/uploads/".$filename,
                    // );
                    // dd($data);
                    // DB::table('property_images')->update($data);     
                } 
                Log::info($key);
            }
            Log::info('num=>'.$k);

        }   
        dd('Image Uploading completed');                           
        
    }
}
