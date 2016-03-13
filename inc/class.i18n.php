<?php

class I18n
{
    public static $class;

    private $translation;

    public static function load($language)
    {
        self::$class = new I18n($language);
    }

    function __construct($language)
    {
        $file = INCLUDES . '/lang/' . $language . '.json';

        if(file_exists($file)) {
            $translationContent = file_get_contents($file);
            $translation = json_decode($translationContent);
        } else {
            $translationContent = file_get_contents(INCLUDES . '/lang/en.json');
            $translation = json_decode($translationContent);
        }

        $this->translation = $translation;
    }

    function get($key)
    {
        if(isset($this->translation[$key])) {
            $text =  $this->translation[$key];
        } else {
            return $key;
        }

        $argsCount = func_num_args();

        if($argsCount > 1)
        {
            $firstArg = func_get_arg(1);
            if(is_array($firstArg))
            {
                foreach($firstArg as $key => $value)
                    $text = str_replace('{' . $key . '}', $value, $text);
            }
            else
            {
                for($i = 1; $i < $argsCount; $i++)
                {
                    $givenParam = func_get_arg($i);
                    $text = str_replace('{' . ($i - 1) . '}', $givenParam, $text);
                }
            }
        }

        return $text;
    }
}