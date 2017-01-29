<?php

class AvatarImage
{
    protected $data, $url, $path;

    /**
     * AvatarImage constructor.
     *
     * @param $url
     * @param $path
     */
    public function __construct($url, $path)
    {
        $this->url = $url;
        $this->path = $path;
    }

    /**
     *
     */
    public function grabImage()
    {
        $ch = curl_init($this->url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $this->data = curl_exec($ch);
        curl_close($ch);
    }

    /**
     *
     */
    public function saveImage()
    {
        $fp = fopen($this->path, 'x');
        fwrite($fp, $this->data);
        fclose($fp);
    }

    /**
     * @param bool $cache
     */
    public function showImage($cache = false)
    {
        if ($cache == true) {
            $image = @imagecreatefrompng($this->path);
        } else {
            $image = @imagecreatefromstring($this->data);
        }
        imagesavealpha($image, true);
        imagepng($image);
        imagedestroy($image);
    }
}
