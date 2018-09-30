<?php
/**
 * Created by PhpStorm.
 * User: Tyler
 * Date: 9/24/2018
 * Time: 10:56 AM
 */

class Image
{

    public $fileName, $image, $imageHeight, $imageWidth;

    function __construct($fileName = "", $image = NULL)
    {

        $this->fileName = $fileName;
        $this->imageHeight = 0;
        $this->imageWidth = 0;

        if ($image != NULL) {
            $this->image = $image;
        }


        if ($fileName != "") {
            $this->load_image();
        }
    }

    function cloneImage()
    {

//            $original = $this->image;
//            $copy = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
//
//            imagecopy($copy, $original, 0, 0, 0, 0, $this->imageWidth, $this->imageHeight);
//            $newImage = new Image("",$copy);
//            return $newImage;
        //Code is from https://stackoverflow.com/questions/12605768/how-to-clone-a-gd-resource-in-php
        //Get the transparent color from a 256 palette image.
        $trans = imagecolortransparent($this->image);
        //If this is a true color image...
        if (imageistruecolor($this->image)) {

            $clone = imagecreatetruecolor($this->imageWidth, $this->imageHeight);
            imagealphablending($clone, false);
            imagesavealpha($clone, true);
        } else {
            $clone = imagecreate($this->imageWidth, $this->imageHeight);

            //If the image has transparency...
            if ($trans >= 0) {

                $rgb = imagecolorsforindex($this->image, $trans);

                imagesavealpha($clone, true);
                $trans_index = imagecolorallocatealpha($clone, $rgb['red'], $rgb['green'], $rgb['blue'], $rgb['alpha']);
                imagefill($clone, 0, 0, $trans_index);
            }
        }
        //Create the Clone!!
        imagecopy($clone, $this->image, 0, 0, 0, 0, $this->imageWidth, $this->imageWidth);

        return new Image("", $clone);
    }

//    function displayImage($fileName){
//        $size = getimagesize($fileName);
//        $image = imagecreatefromjpeg($fileName);
////        header('Content-type: Image/jpeg');
//        imagejpeg($image);
//    }

//    function __clone(){
//        $this->Image = clone $this->image;
//    }
    function getFileName()
    {
        return $this->fileName;
    }

    function load_image()
    {
        $this->image = imagecreatefromjpeg($this->fileName);

        list($this->imageWidth, $this->imageHeight, $type, $attr) = getimagesize($this->fileName);


    }


    function print_image()
    {
        header('Content-type: Image/jpeg');
        imagejpeg($this->image);
    }

    function save_image($outputFile)
    {
        if ($this->image != NULL) {
            imagejpeg($this->image, $outputFile);
        } else {
            echo "Image is NULL";
        }

    }

    function averaging($red, $green, $blue)
    {
        /*
         * Prompt:
         * simply compute the average of the red, green and blue values
         * (used to convert sailboat image):
         * Grey = ( r + g + b ) /3
         */

        $grey = ($red + $green + $blue) / 3;

        return $grey;

    }

    function lightness($red, $green, $blue)
    {
        /*
         * Prompt:
         * Lightness - average just the “brightest” and “darkest” colors:
         * Grey = ( max(r, g, b) + min(r, g, b)) /2
         */
        $grey = (max($red, $green, $blue) + min($red, $green, $blue)) / 2;

        return $grey;


    }

    function luminous($red, $green, $blue, $w1 = .3, $w2 = .6, $w3 = .1)
    {
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

    function getPixelColor($x, $y)
    {
        $pixel = imagecolorat($this->image, $x, $y);

        $red = ($pixel >> 16) & 0xFF;
        $green = ($pixel >> 8) & 0xFF;
        $blue = $pixel & 0xFF;
        $alpha = $pixel ['alpha'];

        return array($red, $green, $blue, $alpha);
    }

    function greyscale($fileNameOut, $method = "averaging")
    {
        $this->load_image();
        list($width, $height, $type, $attr) = getimagesize($this->fileName);

        for ($i = 1; $i < $height; $i++) {
            for ($j = 1; $j < $width; $j++) {
                {


                    $pixel = imagecolorat($this->image, $j, $i);

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
                    $newPixel = imagecolorallocate($this->image, $greyPixel, $greyPixel, $greyPixel);
                    imagesetpixel($this->image, $j, $i, $newPixel);


                }
            }
        }

        $this->save_image($fileNameOut);

    }
    static function printColor($colorName, $color){
        print("$colorName: $color"."<br>");
    }

    static function calculateAlphaPixel( $foregroundPixel, $backgroundPixel)
    {
        $foregroundColors = self::getPixelColors($foregroundPixel);
        $backgroundColors = self::getPixelColors($backgroundPixel);
        $alpha = $foregroundColors[3];

//        self::printColor("red",$foregroundColors[0]);
//        self::printColor("blue", $foregroundColors[1]);
//        self::printColor("green",$foregroundColors[2]);
//        self::printColor("alpha", $foregroundColors[3]);


        return $alpha + $foregroundPixel + (1 - $alpha) * $backgroundPixel;
    }

    static function getPixelColors($pixel)
    {
        $red = ($pixel >> 16) & 0xFF;
        $green = ($pixel >> 8) & 0xFF;
        $blue = $pixel & 0xFF;
        $alpha = (($pixel>> 24) & 0x7F);

        return array($red, $green, $blue, $alpha);
    }

    function comp($foreground, $originX, $originY)
    {
//        $foreGround = new Image($imageFile);
        $backgroundcopy = clone $this;

        for ($x = $originX; $x < $foreground->imageWidth-1; $x++) {
            for ($y = $originY; $y < $foreground->imageHeight-1; $y++) {
                $backgroundPixel = imagecolorat($backgroundcopy->image, $x, $y);
                $foreGroundPixel = imagecolorat($foreground->image, $x, $y);
                $newPixel = self::calculateAlphaPixel($foreGroundPixel,$backgroundPixel);
                imagesetpixel($backgroundcopy->image, $x, $y, $newPixel);
            }
        }

        return $backgroundcopy;


    }

    function displayImage(){
        header('Content-type: Image/jpeg');
        imagejpeg($this->image);
    }


}

function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct)
{
    // creating a cut resource
    $cut = imagecreatetruecolor($src_w, $src_h);

    // copying relevant section from background to the cut resource
    imagecopy($cut, $dst_im, 0, 0, $dst_x, $dst_y, $src_w, $src_h);

    // copying relevant section from watermark to the cut resource
    imagecopy($cut, $src_im, 0, 0, $src_x, $src_y, $src_w, $src_h);

    // insert cut resource to destination image
    print(imagecopymerge($dst_im, $cut, $dst_x, $dst_y, 0, 0, $src_w, $src_h, $pct));
    header('Content-type: Image/jpeg');
    imagejpeg($dst_im);
}

$foregroundFile = "airplane.jpg";
$backgroundFile = "The_Burning_Sky.jpg";

$foregroundImage = new Image($foregroundFile);
$backgroundImage = new Image($backgroundFile);

$newImage = $backgroundImage->comp($foregroundImage,1,1);
$newImage->displayImage();


//$newImage = clone $foregroundImage;
//
//
//$newImage->displayImage();
//

print_r($foregroundImage);
print_r($newImage);
//print_r($foregroundImage);

//$newImage = $foregroundImage->cloneImage();
//print_r($newImage);
//

//print_r($foregroundImage);


//imagecopymerge_alpha($background->image,$background->image, 10, 10, 0, 0, 100, 47, 75);
//imagecopymerge_alpha($foreground->image,$background->image,5,6,0,0,40,40,100);

