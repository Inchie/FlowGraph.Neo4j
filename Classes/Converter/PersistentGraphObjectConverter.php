<?php
namespace FlowGraph\Neo4j\Converter;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use FlowGraph\Neo4j\Domain\Model\Company;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Persistence\PersistenceManagerInterface;

/**
 * Converter for the FlowGraph.Neo4j package
 *
 * @Flow\Scope("singleton")
 */
class PersistentGraphObjectConverter extends \Neos\Flow\Property\TypeConverter\AbstractTypeConverter
{

     /**
     * @var integer
     */
    protected $priority = 100;

    /**
     * @var string
     */
    protected $targetType;

    /**
     * @Flow\Inject
     * @var EntityManagerHelper
     */
    protected $entityManagerHelper;

    /**
     * @var string
     */
    protected $className;

    /**
     * PersistentGraphObjectConverter constructor.
     * @param string $className
     */
    public function __construct($className = Company::class)
    {
        $this->className = $className;
    }

    /**
     * @param mixed $source
     * @param string $targetType
     * @param array $convertedChildProperties
     * @param \Neos\Flow\Property\PropertyMappingConfigurationInterface|NULL $configuration
     * @return mixed|\Neos\Error\Messages\Error
     */
    public function convertFrom($source, $targetType, array $convertedChildProperties = array(), \Neos\Flow\Property\PropertyMappingConfigurationInterface $configuration = NULL)
    {
        $entityManager = $this->entityManagerHelper->getEntityManager();
        $repository = $entityManager->getRepository($this->className);
        return $repository->findOneById(intval($source));
    }
}


