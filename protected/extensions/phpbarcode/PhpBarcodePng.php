<?php

/**
 * Created by PhpStorm.
 * User: nodosauridae
 * Date: 21.01.16
 * Time: 17:00
 */
class PhpBarcodePng extends PhpBarcode
{
    public function init() {
        $x        = 350;  // barcode center
        $y        = 50;  // barcode center
        $height   = 100;   // barcode height in 1D ; module size in 2D
        $width    = 6.9;    // barcode height in 1D ; not use in 2D
        $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
        $code = $this->code; // barcode, of course ;)
        $type     = $this->type;
        $im     = imagecreatetruecolor(700, 100);
        $black  = ImageColorAllocate($im,0x00,0x00,0x00);
        $white  = ImageColorAllocate($im,0xff,0xff,0xff);
        imagefilledrectangle($im, 0, 0, 700, 100, $white);
        imagecolortransparent($im, $white);
        BarcodeNew::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
        ob_start();
        imagepng($im);
        $contents =  ob_get_contents();
        ob_end_clean();
        imagedestroy($im);
        $this->image = base64_encode($contents);
    }
}