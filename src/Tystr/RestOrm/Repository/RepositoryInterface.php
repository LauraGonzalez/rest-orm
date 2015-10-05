<?php

namespace Tystr\RestOrm\Repository;

/**
 * Contract for repositories responsible for handling mapped models.
 *
 * @author Tyler Stroud <tyler@tylerstroud.com>
 */
interface RepositoryInterface
{
    /**
     * @param object $object
     *
     * @return object
     */
    public function save($object);

    /**
     * @param string|int $id
     *
     * @return object
     */
    public function findOneById($id);

    /**
     * @return array|object[]
     */
    public function findAll();

    /**
     * @param object $object
     *
     * @return bool
     */
    public function remove($object);
}