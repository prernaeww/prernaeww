<?php // Code within app\Helpers\Helper.php

namespace App\Helpers;
use Image;
use Carbon\Carbon;
use Storage;

class CommonHelper
{
    public static function resize($dir,$name,$w=300,$h=300)
    {
        $img = Image::make(public_path($dir.$name));
        $img->resize($w,$h, function ($constraint) {
            $constraint->aspectRatio();
        });
        $name = explode('.',$name);
        $new_path = public_path($dir.$name[0].'_'.$w.'x'.$h.".jpg");
        $img->save($new_path);
        return $new_path;
    }

    public static function docsresize($dir,$name,$w=3000,$h=3000)
    {
        $img = Image::make(public_path($dir.$name));
        $img->resize($w,$h, function ($constraint) {
            $constraint->aspectRatio();
        });
        $name = explode('.',$name);
        // dd($name);
        $new_path = public_path($dir.$name[0].'_'.$w.'x'.$h);
        $img->save($new_path);
        return $new_path;
    }

    public static function imageUpload($image,$dir = "images/users")
    {

        $name = time().rand(000, 999).'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $image->move($destinationPath, $name);

        //Self::resize($dir.'/',$name);
        return $name;
    }

    public static function imageUploadByLink($image_link, $dir = "images/users/")
    {

        // $extension = pathinfo($image_link, PATHINFO_EXTENSION);

        // $exte = array('jpeg','png','jpg','gif','svg');

        // if(in_array($extension,$exte))
        // {
            // $filename = time().rand(000, 999).'.'.$extension;
            $filename = time().rand(000, 999).'.jpg';
            $full_path = $dir.$filename;
            $file = file_get_contents($image_link);
            $image = file_put_contents($full_path, $file);


            $data['image_full_link'] = url($full_path);
            $data['image_name'] = $filename;
            $data['status'] = TRUE;
            $data['message'] = 'Image fetched.';
        // }else{
        //     $data['status'] = FALSE;
        //     $data['message'] = 'Only image link allowed.';
        // }
        return $data;
    }

    public static function docsUpload($image,$dir = "images/users")
    {

        $name = time().rand(000, 999).'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $image->move($destinationPath, $name);

        //Self::docsresize($dir.'/',$name);
        return $name;
    }

    public static function imageCatUpload($image,$dir = "images/category")
    {

        $name = time().rand(000, 999).'.'.$image->getClientOriginalExtension();
        $destinationPath = public_path($dir);
        $image->move($destinationPath, $name);

        //Self::resize($dir.'/',$name);
        return $name;
    }

    public static function s3Upload($image,$folder)
    {
        // $request->validate([
        //     'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        // ]);
        $path = Storage::disk('s3')->put($folder, $image);
        // return  $path = Storage::disk('s3')->url($path);
        return  str_replace($folder.'/','',$path);
    }

    public static function ConvertDate($date="",$formate="")
    {
 			// echo Carbon::createFromFormat('d-m-Y',date('Y-m-d'));exit;       
    }
     public static function ConfigSet($key_array)
    {
        foreach ($key_array as $key => $value) {
            $update['value']=$value;
            \App\Models\SystemConfig::wherePath($key)->update($update);
        }
        return true;
    }

    public static function ConfigGet($key)
    {
        $config = \App\Models\SystemConfig::wherePath($key)->first();
        return $config->value;
    }

    public static function RemovePhoneFormat($key)
    {
        $string = str_replace(array( '(+', ')',' ' , '-'), '', $key);
        return $string;
    }
    public static function SetPhoneFormat($key)
    {
        // $string = '(+1'.substr($key, 0, 0).') '.substr($key,0);
        // $string = '+1 '.substr($key,-10, -7).'-'.substr($key,-7, -4).'-'.substr($key, -4);
        // return $string;
        $string =  '1'."(".substr($key, 0, 3).")".substr($key, 3, 3)."-".substr($key,6);
        return $string;
    }
}
