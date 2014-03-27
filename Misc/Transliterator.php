<?php

namespace alkr\CMSBundle\Misc;

class Transliterator extends \Gedmo\Sluggable\Util\Urlizer
{
    private static $table =  array(
        'А' => 'а', 'Б' => 'б', 'В' => 'в', 'Г' => 'г', 'Ѓ' => 'г',
        'Ґ' => 'г`', 'Д' => 'д', 'Е' => 'е', 'Ё' => 'е', 'Є' => 'е',
        'Ж' => 'ж', 'З' => 'з', 'Ѕ' => 'з', 'И' => 'и', 'Й' => 'й',
        'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'к', 'Ќ' => 'к',
        'Л' => 'л', 'Љ' => 'л', 'М' => 'м', 'Н' => 'н', 'Њ' => 'н',
        'О' => 'о', 'П' => 'п', 'Р' => 'р', 'С' => 'с', 'Т' => 'т',
        'У' => 'у', 'Ў' => 'у', 'Ф' => 'ф', 'Х' => 'х', 'Ц' => 'ц',
        'Ч' => 'ч', 'Џ' => 'DH', 'Ш' => 'ш', 'Щ' => 'щ', 'Ъ' => '`',
        'Ы' => 'ы', 'Ь' => '`', 'Э' => 'э`', 'Ю' => 'ю', 'Я' => 'я',
        'а' => 'а', 'б' => 'б', 'в' => 'в', 'г' => 'г', 'ѓ' => 'г',
        'ґ' => 'г', 'д' => 'д', 'е' => 'е', 'ё' => 'ё', 'є' => 'е',
        'ж' => 'ж', 'з' => 'з', 'ѕ' => 'ѕ', 'и' => 'и', 'й' => 'й',
        'ј' => 'ј', 'і' => 'і', 'ї' => 'и', 'к' => 'к', 'ќ' => 'к',
        'л' => 'л', 'љ' => 'љ', 'м' => 'м', 'н' => 'н', 'њ' => 'н',
        'о' => 'о', 'п' => 'п', 'р' => 'р', 'с' => 'с', 'т' => 'т',
        'у' => 'у', 'ў' => 'у', 'ф' => 'ф', 'х' => 'х', 'ц' => 'ц',
        'ч' => 'ч', 'џ' => 'ц', 'ш' => 'ш', 'щ' => 'щ', 'ь' => 'ь',
        'ы' => 'ы', 'ъ' => '`', 'э' => 'э', 'ю' => 'ю', 'я' => 'я'
    );


    public static function transliterate($text, $separator = '-')
    {
        $text = strtr($text, self::$table);
        return trim($text, $separator);
    }

    public static function urlize($text, $separator = '-')
    {
        $text = self::unaccent($text);
        return self::postProcessText($text, $separator);
    }

    private static function postProcessText($text, $separator)
    {
        $text = strtolower($text);

        // Remove all none word characters
        $text = preg_replace('/[^a-zйцукенгшщзхъфывапролджэячсмитьбю]/', ' ', $text);
        
        // More stripping. Replace spaces with dashes
        $text = strtolower(preg_replace('/[^йцукенгшщзхъфывапролджэячсмитьбюA-Za-z0-9\/]+/', $separator,
                           preg_replace('/([a-z\d])([A-Z])/', '\1_\2',
                           preg_replace('/([A-Z]+)([A-Z][a-z])/', '\1_\2',
                           preg_replace('/::/', '/', $text)))));

        return trim($text, $separator);
    }
}