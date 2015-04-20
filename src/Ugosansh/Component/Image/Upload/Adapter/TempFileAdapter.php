<?php

namespace Ugosansh\Component\Image\Upload\Adapter;

use Ugosansh\Component\Image\Upload\TempFileAdapterInterface;
use Ugosansh\Component\Image\Exception\IOException;

/**
 * Temp file adapter
 */
class TempFileAdapter implements TempFileAdapterInterface
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $rootDir;

    /**
     * __construct
     *
     * @param string $tmpDir
     */
    public function __construct($tmpDir = '')
    {
        $this->path   = '';
        $this->tmpDir = $tmpDir ?: sys_get_temp_dir();
    }

    /**
     * Generate tmp file name
     *
     * @return string
     */
    protected function getName()
    {
        return tempnam($this->tmpDir, 'tmpFileAdapter-');
    }

    /**
     * Initialize a new temp file
     *
     * @return TempFileAdapter
     */
    public function create()
    {
        $this->clear();

        $this->path = $this->getName();

        return $this;
    }

    /**
     * write
     *
     * @param string $content
     *
     * @return TempFileAdapter
     * @throws IOException
     */
    public function write($content)
    {
        if ($handle = fopen($this->path, "w+")) {
            if (!fwrite($handle, $content)) {
                fclose($handle);

                $this->clear();

                throw new IOException(sprintf('Failed to write in temp file "%s"', $this->path));
            }

            fclose($handle);
            chmod($this->path, 0755);
        } else {
            $this->clear();

            throw new IOException(sprintf('Failed to open temp file "%s"', $this->path));
        }

        return $this;
    }

    /**
     * Clear the current temp file
     *
     * @return TempFileAdapter
     * @throws IOException
     */
    public function clear()
    {
        if (is_file($this->path)) {
            unlink($this->path);
        }

        $this->path = '';

        echo $this->path;

        return $this;
    }

    /**
     * getPath
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

}
