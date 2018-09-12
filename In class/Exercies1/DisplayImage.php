<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 9/10/2018
 * Time: 2:54 PM
 */


function displayImage($fileName){
    $size = getimagesize($fileName);
    $image = imagecreatefromjpeg($fileName);
    header('Content-type: Image/jpeg');
    imagejpeg($image);
}

$fileName = 'jpeg.jpg';

displayImage($fileName);

?>