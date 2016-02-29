<?php

namespace Ugosansh\Component\Image;

use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;
use Symfony\Component\Filesystem\Exception\IOException as FsIOException;
use Ugosansh\Component\Image\Exception\IOException;

/**
 * Filesystem image tools
 */
class FileSystem
{
    /**
     * @var BaseFilesystem
     */
    protected $filesystem;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * __construct
     *
     * @param string $rootDir
     */
    public function __construct(BaseFilesystem $filesystem, $rootDir)
    {
        $this->filesystem = $filesystem;
        $this->rootDir    = $rootDir;
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

        if ($image->getMimeType() == 'text/html') {
            $image->setMimeType('image/svg+xml');
        }

        $image->setExtension((new MimeType)->extensionToMimeType($image->getMimeType()));
        $image->setWeight(filesize($path));
        $image->setWidth($imageSize[0]);
        $image->setHeight($imageSize[1]);

        return $image;
    }

    /**
     * Create directory
     *
     * @param string $directory Directory path
     * @param mixed  $chmod     Default null
     * @param mixed  $recursive Default false
     *
     * @return boolean
     * @throws Exception\IOException
     */
    public function createDirectory($directory, $chmod = null, $recurisve = true)
    {
        $chmod     = $chmod ?: 755;
        $directory = sprintf('%s/%s', $this->rootDir, trim($directory, '/'));

        if (!$this->filesystem->exists($directory)) {
            try {
                $this->filesystem->mkdir($directory);
            } catch (FsIOException $e) {
                throw new IOException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return true;
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
    public function definePath($id, $title, $extension, $prefix = '', $suffix = '')
    {
        $path = '';

        if ($suffix) {
            $path = sprintf(
                '%s/%s-%s%s-%s.%s',
                $this->getCurrentDirectory(),
                (new \DateTime())->format('His'),
                $prefix,
                Canonicalizer::slug($title),
                $suffix,
                $extension
            );
        } else {
            $path = sprintf(
                '%s/%s-%s%s.%s',
                $this->getCurrentDirectory(),
                (new \DateTime())->format('His'),
                $prefix,
                Canonicalizer::slug($title),
                $extension
            );
        }

        if (file_exists(sprintf('%s/%s', $this->rootDir, $path))) {
            return $this->definePath($id, $title, $extension, $prefix, intval($suffix) + 1);
        }

        return $path;
    }

    /**
     * define final relative path from image
     *
     * @param ImageInterface $image
     *
     * @return string
     */
    public function defineImagePath(ImageInterface $image, $prefix = '')
    {
        return $this->definePath(
            $image->getId(),
            $image->getTitle(),
            $image->getExtension(),
            $prefix
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
            throw new IOException(sprintf('Not found image source "%s"', $image->getId()));
        }

        return file_get_contents($path);
    }

}
