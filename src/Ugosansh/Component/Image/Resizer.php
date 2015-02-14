<?php

namespace Ugosansh\Component\Image;

/**
 * Resizer
 */
class Resizer
{
    const RATIO_NONE   = 0;
    const RATIO_WIDTH  = 1;
    const RATIO_HEIGHT = 2;

    /**
     * @var Resource resource
     */
    private $resource;

    /**
     * @var string source
     */
    private $source;

    /**
     * @var string destination
     */
    private $destination;

    /**
     * @var string mime
     */
    private $mime;

    /**
     * @var int ratio
     */
    private $ratio;

    /**
     * @var int sourceWidth
     */
    private $sourceWidth;

    /**
     * @var int sourceHeight
     */
    private $sourceHeight;

    /**
     * @var int width
     */
    private $width;

    /**
     * @var int height
     */
    private $height;

    /**
     * @var int left
     */
    private $left;

    /**
     * @var int top
     */
    private $top;

    /**
     * __construct 
     */
    public function __construct()
    {
        $this->source = '';
        $this->destination = '';
        $this->mime = '';
        $this->ratio = self::RATIO_WIDTH;
        $this->sourceWidth = 0;
        $this->sourceHeight = 0;
        $this->width = 0;
        $this->height = 0;
        $this->left = 0;
        $this->top = 0;
    }

    /**
     * getResource
     * @return Resource
     */
    private function getResource()
    {
        if ($this->mime == 'image/gif') {
            return imagecreatefromgif($this->source);
        }
        elseif ($this->mime == 'image/jpg' || $this->mime == 'image/jpeg') {
            return imagecreatefromjpeg($this->source);
        }
        elseif ($this->mime == 'image/png') {
            return imagecreatefrompng($this->source);
        }
        else
            return null;

    }

    /**
     * save
     * @param Resource resource
     * @param string destination
     * @return boolean
     */
    public function save($resource, $destination)
    {
        if ($this->mime == 'image/gif') {
            return imagegif($resource, $destination);
        }
        elseif ($this->mime == 'image/jpg' || $this->mime == 'image/jpeg') {
            return imagejpeg($resource, $destination);
        }
        elseif ($this->mime == 'image/png') {
            return imagepng($resource, $destination);
        }
        else
            return false;
    }

    /**
     * defineSize
     * @return array<string, int>
     */
    public function defineSize($width, $height, $ratio = null)
    {
        $ratio = !is_null($ratio) ? $ratio : $this->ratio;

        if ($this->ratio == self::RATIO_HEIGHT)
            return $this->resizeRect($this->sourceWidth, $this->sourceHeight, $width, $height, 'height');
        elseif ($this->ratio == self::RATIO_WIDTH)
            return $this->resizeRect($this->sourceWidth, $this->sourceHeight, $width, $height);
        else
            return array('width' => $width, 'height' => $height);
    }

    /**
     * doResize
     * @param string destination
     * @param int width
     * @param int height
     * @param int cropX [cropX=0]
     * @param int cropY [cropY=0]
     * @param mixed ratio [ratio=null]
     * @param boolean resized [resized=false]
     *
     * @return boolean
     */
    public function doResize($destination, $size, $cropX=0, $cropY=0, $resized=false)
    {
        if (($resource = $this->getResource()) == null) {
            return false;
        }

        $image     = imagecreatetruecolor($size['width'], $size['height']);
        $imageCopy = false;

        if ($resized) {
            $imageCopy = imagecopyresized($image, $resource, 0, 0, $cropX, $cropY, $size['width'], $size['height'], $size['width'], $size['height']);
        }
        else {
            $imageCopy = imagecopyresampled($image, $resource, 0, 0, $cropX, $cropY, $size['width'], $size['height'], $this->sourceWidth, $this->sourceHeight);
        }


        if ($imageCopy) {
            if ($this->save($image, $destination)) {
                imagedestroy($image);

                return true;
            }
            else {
                imagedestroy($image);

                return false;
            }
        }
        else {
            imagedestroy($image);

            return false;
        }
    }

    /**
     * resize
     * @param string destination
     * @param int width
     * @param int height
     * @param int cropX [cropX=0]
     * @param int cropY [cropY=0]
     * @param mixed ratio [ratio=null]
     * @return boolean
     */
    public function resize($destination, $width, $height, $cropX=0, $cropY=0, $ratio=null)
    {
        if ($ratio) {
            $this->ratio = $ratio;
        }

        $size = $this->defineSize($width, $height);

        return $this->doResize($destination, $size, $cropX, $cropY, false);
    }

    /**
     * resizeSection
     * @param string destination
     * @param int width
     * @param int height
     * @param int cropX [cropX=0]
     * @param int cropY [cropY=0]
     * @param mixed ratio [ratio=null]
     * @return boolean
     */
    public function resizeSection($destination, $width, $height, $cropX=0, $cropY=0)
    {
        $size = array('width' => $width, 'height' => $height);

        return $this->doResize($destination, $size, $cropX, $cropY, true);
    }

    /**
     * setSource
     * @param string
     * @return ImageResize
     */
    public function setSource($source)
    {
        if (is_file($source)) {
            $info = getimagesize($source);

            $this->mime         = $info['mime'];
            $this->source       = $source;
            $this->sourceWidth  = $info[0];
            $this->sourceHeight = $info[1];
        }

        return $this;
    }

    public function defineRatio($max, $min) {
        return ($max > 0 && $min > 0) ? round($max / $min, 2) : 0;
    }

    public function resizeRect($width, $height, $newWidth, $newHeight, $priority = 'width') {
        $rectangle = array('width' => $newWidth, 'height' => $newHeight);

        if ($priority == 'height') {
            $ratio = $this->defineRatio($height, $newHeight);

            $rectangle['width'] = round($width / $ratio);

        }
        // priority == width
        else {
            $ratio = $this->defineRatio($width, $newWidth);

            $rectangle['height'] = round($height / $ratio);
        }

        return $rectangle;
    }

    /**
     * getSource
     * @return string
     */
    public function getSource()
    {
        return $this->source;
    }



    /**
     * setDestination
     * @param string destination
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }


    /**
     * getMime
     * @return string
     */
    public function getMime()
    {
        return $this->mime;
    }


    /**
     * setRatio
     * @param int ratio
     * @return void
     */
    public function setRatio($ratio)
    {
        $this->ratio = $ratio;
    }

    /**
     * getRatio
     * @return int
     */
    public function getRatio()
    {
        return $this->ratio;
    }


    /**
     * setWidth
     * @param int width
     * @return void
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * getWidth
     * @return int
     */
    public function getWidth()
    {
        return $this->width;
    }


    /**
     * setHeight
     * @var int height
     * @return ImageResize
     */
    public function setHeight($height)
    {
        $this->height = $height;
    }

    /**
     * getheight
     * @return int
     */
    public function getHeight()
    {
        return $this->height;
    }


    /**
     * setSize
     * @param int width
     * @param int height
     * @return void
     */
    public function setSize($width, $height)
    {
        $this->width = $width;
        $this->height = $height;

        return $this;
    }


    /**
     * setLeft
     * @param int left
     * @return void
     */
    public function setLeft($left)
    {
        $this->left = $left;

        return $this;
    }

    /**
     * getLeft
     * @return int
     */
    public function getLeft()
    {
        return $this->left;
    }


    /**
     * setTop
     * @param int top
     * @return void
     */
    public function setTop($top)
    {
        $this->top = $top;

        return $this;
    }

    /**
     * getTop
     * @return int
     */
    public function getTop()
    {
        return $this->top;
    }

}
