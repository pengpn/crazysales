<?php
/**
 * Created by PhpStorm.
 * User: pengpn
 * Date: 2017/12/14
 * Time: 下午11:52
 */
namespace App\Libraries;
class Picture
{
    public static function isImageExist($imageUrl)
    {
        if (!$imageUrl) return false;

        $ch = curl_init();
        $timeout = 10;
        curl_setopt ($ch, CURLOPT_URL, $imageUrl);
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $contents = curl_exec($ch);
        //echo $contents;
        if (preg_match("/404/", $contents)){
            return false;
        }
        return true;
    }
}