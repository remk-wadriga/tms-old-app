<?php
/**
 * UrlTranslit.php.
 * @author Yuriy Firs <firs.yura@gmail.com>
 */
class UrlTranslit
{
    public static function translit($str) {
        $tr = array(
            "А"=>"a","Б"=>"b","В"=>"v","Г"=>"h", "Ґ"=>"g",
            "Д"=>"d","Е"=>"e","Ж"=>"Zh","З"=>"z","И"=>"y",
            "Й"=>"y","К"=>"k","Л"=>"l","М"=>"m","Н"=>"n",
            "О"=>"o","П"=>"p","Р"=>"r","С"=>"s","Т"=>"t",
            "У"=>"u","Ф"=>"f","Х"=>"h","Ц"=>"ts","Ч"=>"ch",
            "Ш"=>"sh","Щ"=>"sch","Ъ"=>"","Ы"=>"yi","Ь"=>"",
            "Э"=>"e","Ю"=>"yu","Я"=>"ya","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"h","ґ"=>"g","д"=>"d","е"=>"e","ж"=>"zh",
            "з"=>"z","зг"=>"zgh","Зг"=>"zgh","и"=>"y","й"=>"i","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","`"=>"","'"=>"",
            "ы"=>"i","ь"=>"","э"=>"e","ю"=>"iu","я"=>"ia",
            " "=> "-", "."=> "", "/"=> "-", "_"=> "-", "і"=>"i",
            "ї"=>"i","Є"=>"ye","Ї"=>"yi","І"=>"i","є"=>"ie",
        );
        return self::filter(strtr($str,$tr));
    }

    public static function filter($str) {
        $a = array('/(à|á|â|ã|ä|å|æ)/','/(è|é|ê|ë)/','/(ì|í|î|ï)/','/(ð|ò|ó|ô|õ|ö|ø|œ)/','/(ù|ú|û|ü)/','/ç/','/þ/','/ñ/','/ß/','/(ý|ÿ)/','/(=|\+|\/|\\\|\.|\'|\_|\\n| |\(|\))/','/[^a-z0-9_ -]/s','/-{2,}/s');
        $b = array('a','e','i','o','u','c','d','n','ss','y','_','','_');
        return trim(preg_replace($a, $b, strtolower($str)),'_');
    }
}
