<?php

namespace Ugosansh\Component\Image\Upload;

/**
 * Upload adatper interface
 */
interface UploadAdapterInterface
{
    /**
     * upload
     *
     * @param string $source      Source image path
     * @param string $destination Destination image path
     * @param array  $options     Uploader options
     *
     * @return boolean
     */
    public function upload($source, $destination, array $options = []);

    /**
     * Create directory
     *
     * @param string  $directory  Directory path
     * @param integer $chmod
     *
     * @return boolean
     */
    public function createDirectory($directory, $chmod = null, $recurisve = true);

    /**
     * count directory items
     *
     * @param string $directory
     *
     * @return integer
     */
    public function directoryLength($directory = '');

}
