<?php
namespace FlowGraph\Neo4j\Command;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */


use FlowGraph\Neo4j\Domain\Repository\CustomerRepository;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use Neos\Flow\Annotations as Flow;


/**
 * @Flow\Scope("singleton")
 */
class CustomerCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject
     * @var EntityManagerHelper
     */
    protected $entityManagerHelper;

    /**
     * @Flow\Inject
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @param integer $customerId
     */
    public function deleteCommand($customerId)
    {
        $customer = $this->customerRepository->findById($customerId);
        /* @var $customer \FlowGraph\Neo4j\Domain\Model\Customer */

        $this->customerRepository->remove($customer);
        $this->customerRepository->flush();

        $this->outputFormatted('<success>Delete customer successfully.</success>');
    }

    /**
     * @param integer $customerId
     */
    public function deleteRatingsCommand($customerId)
    {
        $customer = $this->customerRepository->findById($customerId);
        /* @var $customer \FlowGraph\Neo4j\Domain\Model\Customer */

        foreach ($customer->getRatings() as $rating) {
            $this->entityManagerHelper->getEntityManager()->remove($rating);
        }
        $this->entityManagerHelper->getEntityManager()->flush();

        $this->outputFormatted('<success>Delete ratings successfully.</success>');
    }
}
