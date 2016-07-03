<?php

class I18n
{
    public static $class;

    private static $group = 'global';
    private static $translation;

    /**
     * @param $language
     */
    public static function load($language)
    {
        $file = ROOT . '/inc/lang/' . $language . '.json';

        if (file_exists($file)) {
            $translationContent = file_get_contents($file);
            $translation = json_decode($translationContent);
        } else {
            $translationContent = file_get_contents(ROOT . '/inc/lang/en.json');
            $translation = json_decode($translationContent);
        }

        self::$translation = $translation;
    }

    public static function setGroup($group = 'global')
    {
        self::$group = $group;
    }

    /**
     * @param $key
     *
     * @return int|mixed|string
     */
    public static function get($key)
    {
        if (isset(self::$translation[self::$group][$key])) {
            $text = self::$translation[self::$group][$key];
        } else {
            return $key;
        }

        $argsCount = func_num_args();

        if ($argsCount > 1) {
            $firstArg = func_get_arg(1);
            if (is_array($firstArg)) {
                foreach ($firstArg as $key => $value) {
                    $text = str_replace('{' . $key . '}', $value, $text);
                }
            } else {
                for ($i = 1; $i < $argsCount; $i++) {
                    $givenParam = func_get_arg($i);
                    $text = str_replace('{' . ($i - 1) . '}', $givenParam, $text);
                }
            }
        }

        return $text;
    }
}