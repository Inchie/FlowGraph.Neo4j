<?php
namespace FlowGraph\Neo4j\Persistence;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphAware\Neo4j\OGM\EntityManager;
use Neos\Flow\Annotations as Flow;

/**
 * Class Repository
 */
abstract class Repository implements RepositoryInterface
{
    /**
     * @Flow\Inject
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var string
     */
    protected $entityClassName;

    /**
     * @param EntityManagerHelper $entityManagerHelper
     */
    public function injectEntityManager(EntityManagerHelper $entityManagerHelper)
    {
        $this->entityManager = $entityManagerHelper->getEntityManager();
    }

    /**
     * Constructor inspired by the neos core team (neos.io)
     */
    public function __construct()
    {
        $this->entityClassName = preg_replace(['/\\\Repository\\\/', '/Repository$/'], ['\\Model\\', ''], get_class($this));
    }

    /**
     * @param $id
     * @return object|null
     */
    public function findById($id)
    {
        return $this->entityManager->getRepository($this->entityClassName)->findOneById($id);
    }

    /**
     * @return array
     */
    public function findAll()
    {
        return $this->entityManager->getRepository($this->entityClassName)->findAll();
    }

    /**
     * @param $entity
     */
    public function remove($entity)
    {
        $this->entityManager->remove($entity);
    }

    /**
     * @param $entity
     * @throws \Exception
     */
    public function persist($entity)
    {
        $this->entityManager->persist($entity);
    }

    /**
     * flush
     */
    public function flush()
    {
        $this->entityManager->flush();
    }
}