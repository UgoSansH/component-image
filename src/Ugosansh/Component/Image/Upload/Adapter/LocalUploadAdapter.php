<?php

namespace Ugosansh\Component\Image\Upload\Adapter;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOException as FsIOException;
use Symfony\Component\Finder\Finder;
use Ugosansh\Component\Image\Upload\Exception\Exception;
use Ugosansh\Component\Image\Upload\Exception\IOException;
use Ugosansh\Component\Image\Upload\UploadAdapterInterface;

/**
 * Local directory uploader
 */
class LocalUploadAdapter implements UploadAdapterInterface
{
    /**
     * @var Filesystem
     */
    protected $fileSystem;

    /**
     * @var Finder
     */
    protected $finder;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * @var integer
     */
    protected $chmod;

    /**
     * __construct
     *
     * @param string $rootDir
     */
    public function __construct(Filesystem $fileSystem, Finder $finder, $rootDir = '', $chmod = 0777)
    {
        $this->fileSystem = $fileSystem;
        $this->finder     = $finder;
        $this->chmod      = $chmod;

        $this->setRootDir($rootDir);
    }

    /**
     * {@inheritdoc}
     */
    public function upload($source, $destination, array $options = [])
    {
        $overwrite = true;
        $chmod     = $this->chmod;
        $recursive = array_key_exists('recursive', $options) ? $options['recursive'] : true;

        if (!is_file($source)) {
            throw new Exception(sprintf('source path "%s" must be a file', $source));
        }

        $directory = substr($destination, 0, strrpos($destination, '/'));

        $this->createDirectory($directory, $chmod, $recursive);

        if (array_key_exists('overwrite', $options) && is_bool($options['overwrite'])) {
            $overwrite = $options['overwrite'];
        }

        if (array_key_exists('chmod', $options)) {
            $chmod = $options['chmod'];
        }

        try {
            $this->fileSystem->rename($source, sprintf('%s/%s', $this->rootDir, $destination), $overwrite);
            //$this->fileSystem->chmod(sprintf('%s/%s', $this->rootDir, $destination), $chmod);
        } catch(FsIOException $e) {
            throw new IOException($e->getMessage(), $e->getCode(), $e);
        }

        return true;
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
        $chmod     = $chmod ?: $this->chmod;
        $directory = sprintf('%s/%s', $this->rootDir, trim($directory, '/'));

        if (!$this->fileSystem->exists($directory)) {
            try {
                $this->fileSystem->mkdir($directory);
            } catch (FsIOException $e) {
                throw new IOException($e->getMessage(), $e->getCode(), $e);
            }
        }

        return true;
    }

    /**
     * count directory items
     *
     * @param string $directory
     *
     * @return integer
     */
    public function directoryLength($directory = '')
    {
        if ($items = glob($this->rootDir .'/'. trim($directory, '/') .'/*')) {
            return count($items);
        }

        return 0;
    }

    /**
     * setRootDir
     *
     * @param string $rootDir
     *
     * @return LocalDirectoryUploader
     */
    public function setRootDir($rootDir)
    {
        $this->rootDir = rtrim($rootDir, '/');

        return $this;
    }

    /**
     * getRootDir
     *
     * @return string
     */
    public function getRootDir()
    {
        return $this->rootDir;
    }

    /**
     * setChmod
     *
     * @param integer $chmod
     *
     * @return LocalDirectoryUmloader
     */
    public function setChmod($chmod)
    {
        $this->chmod = $chmod;

        return $this;
    }

    /**
     * getChmod
     *
     * @return integer
     */
    public function getChmod()
    {
        return $this->chmod;
    }

}
