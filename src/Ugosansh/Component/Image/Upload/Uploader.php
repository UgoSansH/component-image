<?php

namespace Ugosansh\Component\Image\Upload;

use Ugosansh\Component\Image\ImageInterface;
use Ugosansh\Component\Image\MimeType;
use Ugosansh\Component\Image\FileSystem;
use Ugosansh\Component\Image\Upload\Adapter\UploaderAdapterInterface;
use Ugosansh\Component\Image\Upload\TempFileAdapterInterface;

/**
 * Image uploader service
 */
class Uploader
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var TempFile
     */
    protected $tmpFile;

    /**
     * @var FileSystem
     */
    protected $fileSystem;

    /**
     * __construct
     *
     * @param UploaderAdapterInterface $adapter Uploader adapter
     */
    public function __construct(UploadAdapterInterface $adapter = null)
    {
        $this->adapter    = $adapter;
        $this->tmpFile    = null;
        $this->fileSystem = null;
    }

    /**
     * Upload image
     *
     * @param ImageInterface $image   Image entity
     * @param string         $path    Image source path
     * @param array          $options Upload options
     *
     * @return ImageInterface
     */
    public function upload(ImageInterface $image, $path, array $options = [])
    {
        $itemLength  = $this->adapter->directoryLength($this->fileSystem->getCurrentDirectory());
        $image       = $this->fileSystem->hydrateImageInfo($image, $path);
        $destination = $this->fileSystem->defineImagePath($image, $itemLength);

        $this->adapter->upload($path, $destination, $options);
        $image->setPath($destination);

        return $image;
    }

    /**
     * Upload by base64 encoded source
     *
     * @param ImageInterface $image   Image entity
     * @param string         $content Base64 source
     * @param array          $options Upload options
     *
     * @return ImageInterface
     */
    public function uploadBase64(ImageInterface $image, $content, array $options = [])
    {
        $this
            ->tmpFile
            ->create()
            ->write(base64_decode($content));

        try {
            $image = $this->upload($image, $this->tmpFile->getPath(), $options);
        } finally {
            $this->tmpFile->clear();
        }

        return $image;
    }

    /**
     * Set adapter
     *
     * @param UploadAdapterInterface $adapter
     *
     * @return Uploader
     */
    public function setAdapter(UploadAdapterInterface $adapter)
    {
        $this->adapter = $adapter;

        return $this;
    }

    /**
     * Set tmpFile
     *
     * @param TempFileAdapterInterface $tmpFile
     *
     * @return Uploader
     */
    public function setTempFileAdapter(TempFileAdapterInterface $tmpFile)
    {
        $this->tmpFile = $tmpFile;

        return $this;
    }

    /**
     * Set fileSystem
     *
     * @param FileSystem $fileSystem
     *
     * @return Uploader
     */
    public function setFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;

        return $this;
    }

}
