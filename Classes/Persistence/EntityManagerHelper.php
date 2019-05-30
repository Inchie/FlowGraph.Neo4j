<?php
namespace FlowGraph\Neo4j\Persistence;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphAware\Neo4j\OGM\EntityManager;
use Neos\Flow\Persistence\Exception;
use Neos\Flow\Annotations as Flow;


/**
 * EntityManagerHelper
 *
 * @Flow\Scope("singleton")
 */
class EntityManagerHelper
{
    /**
     * @var string
     * @Flow\InjectConfiguration("storage.protocol")
     */
    protected $protocol;

    /**
     * @var string
     * @Flow\InjectConfiguration("storage.host")
     */
    protected $host;

    /**
     * @var string
     * @Flow\InjectConfiguration("storage.port")
     */
    protected $port;

    /**
     * @var string
     * @Flow\InjectConfiguration("storage.user")
     */
    protected $user;

    /**
     * @var string
     * @Flow\InjectConfiguration("storage.password")
     */
    protected $password;

    /**
     * @var EntityManager|NULL
     */
    protected $cachedEntityManager;

    /**
     * @return EntityManager
     * @throws Exception
     */
    public function getEntityManager()
    {
        if (!$this->cachedEntityManager instanceof EntityManager) {
            $this->cachedEntityManager = EntityManager::create($this->createUri());
        }
        return $this->cachedEntityManager;
    }

    /**
     * ToDo Refactor asap!
     * @return string
     * @throws Exception
     */
    protected function createUri()
    {
        if (strlen($this->protocol) === 0 || strlen($this->host) === 0 || strlen($this->port) === 0) {
            throw new Exception('Storage settings: Protocol, host and port cannot be empty.', 1559069623);
        }

        if (strlen($this->user) === 0 || strlen($this->password) === 0) {
            return $this->protocol . '://'
                . $this->host . ':'
                . $this->port;
        } else {
            return $this->protocol . '://'
                . $this->user . ':'
                . $this->password . '@'
                . $this->host . ':'
                . $this->port;
        }
    }
}

