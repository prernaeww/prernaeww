<?php
 
namespace App\Services;
 
 
class AppleToken
{

 
    public function generate()
    {
		$path=str_replace('\\','/',Storage::path("client.rb")) ;
		$command='ruby '.$path;
		$exec=shell_exec($command);
 
        return $exec;
    }
}