<?php

namespace Ugosansh\Component\Image;

/**
 * Image manager interface
 */
interface ImageManagerInterface
{
    /**
     * find an image by primary key
     *
     * @param mixed $id
     *
     * @return ImageInterface
     */
    public function find($id);

    /**
     * find unique image by criterias
     *
     * @param array   $criterias
     * @param mixed   $offset    null|integer
     * @param integer $limit     default 100
     *
     * @return array
     */
    public function findOneBy(array $criterias);

    /**
     * find images by criterias
     *
     * @param array   $criterias
     * @param mixed   $orderBy   null|array
     * @param mixed   $offset    null|integer
     * @param integer $limit     default 100
     *
     * @return array
     */
    public function findBy(array $criterias, array $orderBy = [], $offset = null, $limit = 100);

    /**
     * save image entity
     *
     * @param ImageInterface $image
     *
     * @return boolean
     */
    public function save(ImageInterface $image);

    /**
     * delete
     *
     * @param ImageInterface $image
     *
     * @return boolean
     */
    public function remove(ImageInterface $image);

}