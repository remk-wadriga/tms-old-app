<?php
/**
 * Created by PhpStorm.
 * User: Deniat
 * Date: 14.09.2015
 * Time: 14:36
 */
class PriceToTextConverter
{
    public static function convert($num, $isPrice=true) {
        $nul='нуль';
        $ten=array(
            array('','один','два','три','чотири','п`ять','шість','сім', 'вісім','дев`ять'),
            array('','одна','дві','три','чотири','п`ять','шість','сім', 'вісім','дев`ять'),
        );
        $a20=array('десять','одинадцять','дванадцять','тринадцять','чотирнадцять' ,'п`ятнадцять','шістнадцять','сімнадцять','вісімнадцять','дев`ятнадцять');
        $tens=array(2=>'двадцять','тридцять','сорок','п`ятдесят','шістдесят','сімдесят' ,'вісімдесят','дев`яносто');
        $hundred=array('','сто','двісті','триста','чотириста','п`ятьсот','шістьсот', 'сімьсот','вісімсот','дев`ятьсот');
        $unit = array( // Units
            array('копійка', 'копійки', 'копійок', 1),
            array('гривня', 'гривні', 'гривень', 1),
            array('тисяча', 'тисячі', 'тисяч', 1),
            array('мільйон', 'мільйона', 'мільйонів', 0),
            array('мільярд', 'мільярда', 'мільярдів', 0),
        );
        list($rub,$kop) = explode('.',sprintf("%015.2f", floatval($num)));
        $out = array();
        if (intval($rub)>0) {
            foreach(str_split($rub,3) as $uk=>$v) {
                if (!intval($v)) continue;
                $uk = sizeof($unit)-$uk-1;
                $gender = $unit[$uk][3];
                list($i1,$i2,$i3) = array_map('intval',str_split($v,1));
                $out[] = $hundred[$i1];
                if ($i2>1) $out[]= $tens[$i2].' '.$ten[$gender][$i3];
                else $out[]= $i2>0 ? $a20[$i3] : $ten[$gender][$i3];
                if ($uk>1) $out[]= self::morph($v,$unit[$uk][0],$unit[$uk][1],$unit[$uk][2]);
            }
        }
        else $out[] = $nul;
        if($isPrice)$out[] = self::morph(intval($rub), $unit[1][0],$unit[1][1],$unit[1][2]);
        if($isPrice)$out[] = $kop.' '.self::morph($kop,$unit[0][0],$unit[0][1],$unit[0][2]);
        return trim(preg_replace('/ {2,}/', ' ', join(' ',$out)));
    }

    static function morph($n, $f1, $f2, $f5) {
        $n = abs(intval($n)) % 100;
        if ($n>10 && $n<20) return $f5;
        $n = $n % 10;
        if ($n>1 && $n<5) return $f2;
        if ($n==1) return $f1;
        return $f5;
    }
}