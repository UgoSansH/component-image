<?php

namespace Ugosansh\Component\Image;

/**
 * Image Interface
 */
interface ImageInterface
{
    /**
     * getId
     *
     * @return mixed
     */
    public function getId();

    /**
     * setTitle
     *
     * @param string $title
     *
     * @return ImageInterface
     */
    public function setTitle($title);

    /**
     * getTitle
     *
     * @return string
     */
    public function getTitle();

    /**
     * setSlug
     *
     * @param string $slug
     *
     * @return ImageInterface
     */
    public function setSlug($slug);

    /**
     * getSlug
     *
     * @return string
     */
    public function getSlug();

    /**
     * setPath
     *
     * @param stirng $path
     *
     * @return ImageInterface
     */
    public function setPath($path);

    /**
     * getPath
     *
     * @return stirng
     */
    public function getPath();

    /**
     * setMimeType
     *
     * @param string $mimeType
     *
     * @return ImageInterface
     */
    public function setMimeType($mimeType);

    /**
     * getMimeType
     *
     * @return string
     */
    public function getMimeType();

    /**
     * setExtension
     *
     * @param string $extension
     *
     * @return ImageInterface
     */
    public function setExtension($extension);

    /**
     * getExtension
     *
     * @return string
     */
    public function getExtension();

    /**
     * setWidth
     *
     * @param integer $width
     *
     * @return ImageInterface
     */
    public function setWidth($width);

    /**
     * getWidth
     *
     * @return integer
     */
    public function getWidth();

    /**
     * setHeight
     *
     * @param integer $height
     *
     * @return ImageInterface
     */
    public function setHeight($height);

    /**
     * getHeight
     *
     * @return integer
     */
    public function getHeight();

    /**
     * setWeight
     *
     * @param integer $weight
     *
     * @return ImageInterface
     */
    public function setWeight($weight);

    /**
     * getWeight
     *
     * @return integer
     */
    public function getWeight();

    /**
     * Set metadata
     *
     * @param array $metadata
     *
     * @return ImageInterface
     */
    public function setMetadata(array $metadata);

    /**
     * Get metadata
     *
     * @return array
     */
    public function getMetadata();

    /**
     * Add metadata
     *
     * @param string $name
     * @param string $value
     *
     * @return ImageInterface
     */
    public function addMetadata($name, $value);

    /**
     * Remove metadata
     *
     * @param string $name
     *
     * @return ImageInterface
     */
    public function removeMetadata($name);

    /**
     * Set parent
     *
     * @param ImageInterface $image
     *
     * @return ImageInterface
     */
    public function setParent(ImageInterface $image);

    /**
     * Get parent
     *
     * @return ImageInterface
     */
    public function getParent();

}
