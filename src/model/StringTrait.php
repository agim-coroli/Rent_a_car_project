<?php
namespace model;

use Exception;

trait StringTrait{

    public static function slugify(string $text, $prefix=true, string $separator = '-'): string
    {
        $text = preg_replace('~[^\pL\d]+~u', $separator, $text);

        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        $text = preg_replace('~[^-\w]+~', '', $text);

        $text = trim($text, $separator);

        $text = preg_replace('~-+~', $separator, $text);

        $text = strtolower($text);

        if (empty($text)) {
            throw new Exception("Échec de la transformation en slug : le texte est vide ou ne contient pas de caractères valides");
        }

        if($prefix===true) {
            $text = bin2hex(random_bytes(2)) . "-" . $text;
        }

        return $text;
    }

    public static function cutTheText(string $text, int $maxLength=200): string
    {
        if(strlen($text)<=$maxLength){
            return $text;
        }
        $cutText = substr($text,0,$maxLength);
        $lastSpace = strrpos($cutText,' ');
        if($lastSpace!==false){
            $cutText = substr($cutText,0,$lastSpace);
        }
        $cutText .= '...';
        return $cutText;
    }
}