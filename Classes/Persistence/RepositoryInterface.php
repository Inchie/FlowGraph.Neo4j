<?php
namespace FlowGraph\Neo4j\Persistence;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

/**
 * Interface RepositoryInterface
 * @package FlowGraph\Neo4j\Persistence
 */
interface RepositoryInterface
{
    /**
     * @return mixed
     */
    public function findAll();

    /**
     * @param integer $id
     * @return mixed
     */
    public function findById($id);

    /**
     * @param $entity
     * @return mixed
     */
    public function remove($entity);

    /**
     * @param $entity
     * @return mixed
     */
    public function persist($entity);

    /**
     * @return mixed
     */
    public function flush();
}

