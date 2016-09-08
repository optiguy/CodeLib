<?php
/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 30-08-2016
 * Time: 21:19
 */

namespace Cache;

class File
{
    public static function createCache($arr, $name)
    {
        $fh = fopen(getcwd().'/cache/'.$name.'.cache.php', 'w+') or die('Error:cache');
        fwrite($fh, $arr);
        fclose($fh);
    }
    public static function readCache($name)
    {
        $file = getcwd().'/cache/'.$name.'.cache.php';
        if(file_exists($file))
        {
            $myfile = fopen($file, "r");
            $output = fgets($myfile);
            fclose($myfile);
            return $output;
        }
        return false;
    }
}