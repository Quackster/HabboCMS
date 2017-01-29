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
        self::$group = (string)$group;
    }

    /**
     * @param       $key
     * @param array $replace
     * @param null  $group
     *
     * @return string
     */
    public static function get($key, $replace = array(), $group = null)
    {
        if (is_null($group)) {
            $group = self::$group;
        }

        if (isset(self::$translation[$group][$key])) {
            $text = self::$translation[$group][$key];
        } else {
            return (string)$key;
        }

        if (is_array($replace) && count($replace) > 0) {
            foreach ($replace as $key => $value) {
                $text = str_replace('{' . $key . '}', $value, $text);
            }
        }

        return (string)$text;
    }
}