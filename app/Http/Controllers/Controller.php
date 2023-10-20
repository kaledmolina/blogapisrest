<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function saveImage($image, $path = 'public'){
        if (!$image){
            return null;
        }
        $filename = time().'.png';
        //guardar imagen en el servidor
        Storage::disk($path)->put($filename, base64_decode($image)); 
        //retornar la ruta de la imagen
        return URL::to('/').'/storage/'.$path.'/'.$filename;     
    }
}
