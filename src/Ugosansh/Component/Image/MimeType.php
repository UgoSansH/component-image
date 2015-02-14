<?php

namespace Ugosansh\Component\Image;

/**
 * Image mime type service
 */
class MimeType
{
    const TYPE_PNG  = 'image/png';
    const TYPE_GIF  = 'image/gif';
    const TYPE_JPEG = 'image/jpg|image/jpeg';

    /**
     * @var array
     */
    protected $mimeTypes;

    /**
     * __construct
     */
    public function __construct()
    {
        $this->mimeTypes = [
            'png'  => 'image/png',
            'jpeg' => 'image/jpeg',
            'jpg'  => 'image/jpg',
            'gif'  => 'image/gif'
        ];
    }

    /**
     * Define extension by mime type
     *
     * @param string $mimeType
     *
     * @return mixed string|boolean
     */
    public function extensionToMimeType($mimeType)
    {
        return array_search($mimeType, $this->mimeTypes);
    }

    /**
     * get file mime type
     *
     * @param stirng $path image file path
     *
     * @return string
     */
    public static function getType($path)
    {
        if (!is_file($path)) {
            throw new \InvalidArgumentException(sprintf('invalid filename or path "%s"', $path));
        }

        $info = (new \finfo(FILEINFO_MIME))->file($path);

        return substr($info, 0, strpos($info, ';'));
    }

    /**
     * is mime type
     *
     * @param stirng $path image file path
     *
     * @return boolean
     */
    public function isType($path, $mimeType)
    {
        $mimeTypes = explode('|', $mimeType);

        return (in_array(self::getType($path), $mimeTypes));
    }

    /**
     * validate mime type file
     *
     * @param stirng $path image file path
     *
     * @return boolean
     */
    public function validate($path, array $mimeTypes = [])
    {
        if (empty($mimeTypes)) {
            $mimeTypes = $this->mimeTypes;
        }

        return in_array(self::getType($path), $mimeTypes);
    }

    /**
     * validate supported mime type
     *
     * @param string $mimeType
     *
     * @return boolean
     */
    public function validateType($mimeType)
    {
        return in_array($mimeType, $this->mimeTypes);
    }

    /**
     * addMimeType
     *
     * @param stirng $mimeType
     *
     * @return MimeType
     */
    public function addMimeType($mimeType)
    {
        if (!in_array($mimeType, $this->mimeTypes)) {
            $this->mimeTypes[] = $mimeType;
        }

        return $this;
    }

    /**
     * getMimeTypes
     *
     * @return array
     */
    public function getMimeTypes()
    {
        return $this->mimeTypes;
    }

}
