<?php

namespace Ugosansh\Component\Image\Upload;

/**
 * Temp file adapter interface
 */
interface TempFileAdapterInterface
{
    /**
     * __construct
     *
     * @param string $tmpDir
     */
    public function __construct($tmpDir = '');

    /**
     * Initialize a new temp file
     *
     * @return TempFileAdapter
     */
    public function create();

    /**
     * write
     *
     * @param string $content
     *
     * @return TempFileAdapter
     * @throws IOException
     */
    public function write($content);

    /**
     * Clear the current temp file
     *
     * @return TempFileAdapter
     * @throws IOException
     */
    public function clear();

    /**
     * getPath
     *
     * @return string
     */
    public function getPath();

}
