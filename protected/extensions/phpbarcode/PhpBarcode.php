<?php

/**
 * Created by PhpStorm.
 * User: elvis
 * Date: 18.11.15
 * Time: 13:15
 */
class PhpBarcode
{
    public $code     = '123456789012'; // barcode, of course ;)
    public $type = 'code128';
    public $image = "";

    public function init() {
        $fontSize = 10;   // GD1 in px ; GD2 in point
        $marge    = 10;   // between barcode and hri in pixel
        $x        = 175;  // barcode center
        $y        = 25;  // barcode center
        $height   = 50;   // barcode height in 1D ; module size in 2D
        $width    = 3.45;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation

        $code = $this->code; // barcode, of course ;)
        $type     = $this->type;

        $im     = imagecreatetruecolor(350, 50);
        $black  = ImageColorAllocate($im,0x00,0x00,0x00);
        $white  = ImageColorAllocate($im,0xff,0xff,0xff);
        imagefilledrectangle($im, 0, 0, 350, 50, $white);

        BarcodeNew::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);


//        if ( isset($font) ){
//            $box = imagettfbbox($fontSize, 0, $font, $data['hri']);
//            $len = $box[2] - $box[0];
//            Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
//            imagettftext($im, $fontSize, $angle, $x + $xt, $y + $yt, $blue, $font, $data['hri']);
//        }

//        imageline($im, $x, 0, $x, 250, $red);
//        imageline($im, 0, $y, 250, $y, $red);

        // -------------------------------------------------- //
        //                  BARCODE BOUNDARIES
        // -------------------------------------------------- //
//        for($i=1; $i<5; $i++){
//            drawCross($im, $blue, $data['p'.$i]['x'], $data['p'.$i]['y']);
//        }

        // -------------------------------------------------- //
        //                    GENERATE
        // -------------------------------------------------- //
//        header('Content-type: image/gif');
        $this->image = Yii::getPathOfAlias("webroot")."/".uniqid("barcode_".$this->code).".gif";
        imagegif($im, $this->image);
        imagedestroy($im);
    }

    public function run()
    {
        return $this->image;
    }

}
