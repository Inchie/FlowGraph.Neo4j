<?php
namespace FlowGraph\Neo4j\Command;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */


use FlowGraph\Neo4j\Domain\Repository\CompanyRepository;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use GraphAware\Neo4j\OGM\Common\Collection;
use Neos\Flow\Annotations as Flow;

/**
 * @Flow\Scope("singleton")
 */
class CompanyCommandController extends \Neos\Flow\Cli\CommandController
{
    /**
     * @Flow\Inject
     * @var EntityManagerHelper
     */
    protected $entityManagerHelper;

    /**
     * @Flow\Inject
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @param integer $companyId
     */
    public function deleteRatingsCommand($companyId)
    {
        $company = $this->companyRepository->findById($companyId);
        /* @var $company \FlowGraph\Neo4j\Domain\Model\Company */

        $company->setRatings(
            new Collection()
        );
        $this->entityManagerHelper->getEntityManager()->flush();

        $this->outputFormatted('<success>Delete ratings successfully.</success>');
    }
}
