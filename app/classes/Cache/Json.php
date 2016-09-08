<?php

/**
 * Created by PhpStorm.
 * User: liltoto
 * Date: 30-08-2016
 * Time: 21:00
 */
namespace Cache;

class Json
{
    public static function createCache($arr, $name)
    {
        $output = json_encode($arr);

        $fh = fopen(getcwd().'/cache/'.$name.'.cache.php', 'w+') or die('Error:cache');
        fwrite($fh, $output);
        fclose($fh);
    }
}