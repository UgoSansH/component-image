<?php

namespace Ugosansh\Component\Image;

/**
 * Filesystem image tools
 */
class FileSystem
{
    /**
     * @var string
     */
    protected $rootDir;

    /**
     * __construct
     *
     * @param string $rootDir
     */
    public function __construct($rootDir)
    {
        $this->rootDir = $rootDir;
    }

    /**
     * define the current valid directory path
     *
     * @return string
     */
    public static function getCurrentDirectory()
    {
        return (new \DateTime())->format('Y/m/d');
    }

    /**
     * Hydrate image info
     *
     * @param ImageInterface $image
     * @param string         $path
     *
     * @return ImageInterface
     */
    public static function hydrateImageInfo(ImageInterface $image, $path)
    {
        $imageSize = getimagesize($path);
        $finfo     = new \finfo(FILEINFO_MIME_TYPE);

        $image->setMimeType($finfo->file($path));
        $image->setExtension((new MimeType)->extensionToMimeType($image->getMimeType()));
        $image->setWeight(filesize($path));
        $image->setWidth($imageSize[0]);
        $image->setHeight($imageSize[1]);

        return $image;
    }

    /**
     * definePath
     *
     * @param integer $id
     * @param string $title
     * @param string $extension
     *
     * @return string
     */
    public function definePath($id, $title, $extension)
    {
        return sprintf(
            '%s/%d-%s.%s',
            $this->getCurrentDirectory(),
            $id,
            Canonicalizer::slug($title),
            $extension
        );
    }

    /**
     * define final relative path from image
     *
     * @param ImageInterface $image
     *
     * @return string
     */
    public function defineImagePath(ImageInterface $image)
    {
        return $this->definePath(
            $image->getId(),
            $image->getTitle(),
            $image->getExtension()
        );
    }

    /**
     * get absolute image path
     *
     * @param string $path
     *
     * @return string
     */
    public function getAbsolutePath($path)
    {
        return sprintf('%s/%s', $this->rootDir, trim($path, '/'));
    }

    /**
     * get source image
     *
     * @param ImageInterface $image
     *
     * @return string
     */
    public function getImageSource(ImageInterface $image)
    {
        $path = $this->getAbsolutePath($image->getPath());

        if (!is_file($path)) {
            throw Exception\IOException(sprintf('Not found image source "%s"', $image->getId()));
        }

        return file_get_contents($path);
    }

}