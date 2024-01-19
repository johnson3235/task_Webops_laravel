<?php
namespace App\Traits;
use Storage;
trait Media {


    public static function upload(String $title ,$image,string $dir) :string
    {
        $extension  = $image->getClientOriginalExtension(); 
        $image_name = time() .'_' . $title . '.' . $extension;
        $path = $image->storeAs(
            $dir.'/', $image_name, 's3'
        );
        // $photoName = uniqid() . '.' . $image->extension();
        // $t=Storage::disk('s3')->put($photoName, file_get_contents($image), 'public');
        // $image->move(public_path("/storage/$dir"),$photoName);
        return $path!='' || $path != null ? $image_name: null;
    }


    public static function upload2(String $title ,$image,string $dir) :string
    {
        $image_name =  uniqid() .'_' . $title . '.' .'png';
        // $path = $image->storeAs(
        //     $dir.'/', $image_name, 's3'
        // );
        $path = Storage::disk('s3')->put($dir.'/'.$image_name, $image);
        // Storage::put($dir . $image_name, $image);
        
        
        // $photoName = uniqid() . '.' . $image->extension();
        // $t=Storage::disk('s3')->put($photoName, file_get_contents($image), 'public');
        // $image->move(public_path("/storage/$dir"),$photoName);
        return $path!='' || $path != null ? $image_name: null;
    }
    public static function delete(string $fullPublicPath) :bool
    {
        $oldPhotoPath = public_path("{$fullPublicPath}");
        if (file_exists($oldPhotoPath)) {
            unlink($oldPhotoPath);
            return true;
        }
        return false;
    }
}