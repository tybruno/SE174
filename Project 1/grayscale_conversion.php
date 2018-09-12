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

}

function lightness($red,$green,$blue){
    /*
     * Prompt:
     * Lightness - average just the “brightest” and “darkest” colors:
     * Grey = ( max(r, g, b) + min(r, g, b)) /2
     */




}

function luminous($red,$green,$blue,$w1 = 1,$w2 = 1,$w3 = 1){
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
}

function convertImageToGrayScale( $fileNameIn , $fileNameOut, $method){
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

}



?>