<?php
/**
 * Created by PhpStorm.
 * User: Tyler Bruno
 * Date: 9/12/2018
 * Time: 2:47 PM
 */

function averaging($red,$green,$blue){
    /*
     * Prompt:
     * simply compute the average of the red, green and blue values
     * (used to convert sailboat image):
     * Grey = ( r + g + b ) /3
     */

    $grey = ($red + $green + $blue)/3;

    return $grey;

}

function lightness($red,$green,$blue){
    /*
     * Prompt:
     * Lightness - average just the “brightest” and “darkest” colors:
     * Grey = ( max(r, g, b) + min(r, g, b)) /2
     */
    $grey = (max($red,$green,$blue) + min($red,$green,$blue))/2;

    return $grey;



}

function luminous($red,$green,$blue,$w1 = .3,$w2 = .6,$w3 = .1){
    /*
     * Prompt:
     * Luminous – applies “weighted” values to each r, g and b component based on experiment results
     * on human perception of colors.
     * Grey = w1 * r + w2 * g + w3 * b; where w1 + w2 + w3 = 1;
     * Humans perceive green more strongly than other colors,
     * so this weight for the green value is higher than the other values.
     * (For example: try w1 = .3 w2 = .6 w3 = .1).
     * You can experiment with these weights to determine which value you prefer.
     * It is really a creative choice.
     */

    $grey = $w1 * $red + $w2 * $green + $w3 * $blue;
    return $grey;
}

function convertImageToGrayScale( $fileNameIn , $fileNameOut, $method = "averaging"){
    /*
     * Prompt:
     * Create a new function (from scratch):
     * int convertImageToGrayScale( $fileNameIn , $fileNameOut, $method)
     * The string $fileNameIn specifies the source image file.
     * The string $fileNameOut specifies the destination image file created by the function.
     * The source image file is not modified.
     * The int $method specifies the greyscale conversion method.
     * Your function should support all three (3) methods specified above.
     * The function should return True if the conversion is successful otherwise false;
     * You can optionally return the number of pixels converted (image width * height)
     * if you want to return a more meaningful value instead of just True;
     */
    try {
        $startTime = microtime(true);
        $img = imagecreatefromjpeg($fileNameIn);

        list($width, $height, $type, $attr) = getimagesize($fileNameIn);

        for ($i = 1; $i < $height; $i++) {
            for ($j = 1; $j < $width; $j++) {
                {


                    $pixel = imagecolorat($img, $j, $i);

                    $red = ($pixel >> 16) & 0xFF;
                    $green = ($pixel >> 8) & 0xFF;
                    $blue = $pixel & 0xFF;

                    if ($method == "averaging") {
                        $greyPixel = averaging($red, $green, $blue);
                    } elseif ($method == "lightness") {
                        $greyPixel = lightness($red, $green, $blue);
                    } else {
                        $greyPixel = luminous($red, $green, $blue);

                    }
                    $newPixel = imagecolorallocate($img, $greyPixel, $greyPixel, $greyPixel);
                    imagesetpixel($img, $j, $i, $newPixel);


                }
            }
        }

        imagejpeg($img, $fileNameOut);
        imagedestroy($img);
        echo("$fileNameOut <br>");
        $wAndH= $width * $height;
        echo("Img Size: ". $wAndH . "\n");
        echo("<br>");
        echo('File: '. $fileNameIn."\n<br>");
        echo("Time taken to complete " . $method . " was " . (microtime(true) - $startTime) . " seconds\n");
        echo("<br>");

        return $wAndH;
    }
    catch (Exception $e){
        echo($e);
        return false;
    }

}
define('AVERAGING','averaging');
define('LUMINOUS','luminous');
define('LIGHTNESS','lightness');
$extension = ".jpg";

//Sailboat testing
$imgName = "sailboat";
$fileName = $imgName . $extension;
$method= AVERAGING;
$outputFile = $imgName. $method. $extension;

convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);
$method= LIGHTNESS;
$outputFile = $imgName. $method.$extension;
convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);
$method= LUMINOUS;
$outputFile = $imgName. $method.$extension;
convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);

//Jpeg testing
$imgName = "jpeg";
$method= AVERAGING;
$fileName = $imgName . $extension;
$outputFile = $imgName. $method.$extension;
convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);
$method= LIGHTNESS;
$outputFile = $imgName. $method.$extension;
convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);
$method= LUMINOUS;
$outputFile = $imgName. $method.$extension;
convertImageToGrayScale($fileNameIn=$fileName,$fileNameOut=$outputFile,$method=$method);



?>