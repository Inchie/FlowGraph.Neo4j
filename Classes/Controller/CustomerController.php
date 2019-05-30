<?php
namespace FlowGraph\Neo4j\Controller;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use FlowGraph\Neo4j\Domain\Model\Company;
use FlowGraph\Neo4j\Domain\Model\Customer;
use FlowGraph\Neo4j\Domain\Repository\CompanyRepository;
use FlowGraph\Neo4j\Converter\PersistentGraphObjectConverter;
use FlowGraph\Neo4j\Domain\Repository\CustomerRepository;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\ObjectManagement\Exception\UnresolvedDependenciesException;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Annotations as Flow;

/**
 * Class CustomerController
 * @package FlowGraph\Neo4j\Controller
 */
class CustomerController extends ActionController
{
    /**
     * @Flow\Inject
     * @var CustomerRepository
     */
    protected $customerRepository;

    /**
     * @Flow\Inject
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @Flow\Inject
     * @var EntityManagerHelper
     */
    protected $entityManagerHelper;

    /**
     * @param Customer $newCustomer
     * @throws StopActionException
     * @throws \Exception
     */
    public function createAction(Customer $newCustomer)
    {
        $this->customerRepository->persist($newCustomer);
        $this->customerRepository->flush();

        $this->addFlashMessage(
            'Create customer ' . $newCustomer->getName() . ' successfully'
        );
        $this->redirect('list');
    }

    /**
     * list action
     */
    public function listAction()
    {
        $this->view->assign('customers', $this->customerRepository->findAll());
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeShowAction()
    {
        /* @var $customerMappingConfiguration PropertyMappingConfiguration */
        $customerMappingConfiguration = $this->arguments['customer']->getPropertyMappingConfiguration();
        $customerMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Customer::class));
    }

    /**
     * @param Customer $customer
     */
    public function showAction(Customer $customer)
    {
        $this->view->assign('customer', $customer)
            ->assign('companies', $this->companyRepository->findAll());
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeRatePositiveAction()
    {
        /* @var $customerMappingConfiguration PropertyMappingConfiguration */
        $customerMappingConfiguration = $this->arguments['customer']->getPropertyMappingConfiguration();
        $customerMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Customer::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Customer $customer
     * @param Company $company
     * @throws StopActionException
     * @throws UnresolvedDependenciesException
     */
    public function ratePositiveAction(Customer $customer, Company $company)
    {
        if(!$customer->hasRatedCompany($company)) {
            $customer->rateCompanyWithScore($company, 1);
            $this->entityManagerHelper->getEntityManager()->flush();
        }

        $this->redirect(
            'show',
            'Customer',
            'FlowGraph.Neo4j',
            ['customer' => $customer->getId()]
        );
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeRateNegativeAction()
    {
        /* @var $customerMappingConfiguration PropertyMappingConfiguration */
        $customerMappingConfiguration = $this->arguments['customer']->getPropertyMappingConfiguration();
        $customerMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Customer::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Customer $customer
     * @param Company $company
     * @throws StopActionException
     * @throws UnresolvedDependenciesException
     */
    public function rateNegativeAction(Customer $customer, Company $company)
    {
        if(!$customer->hasRatedCompany($company)) {
            $customer->rateCompanyWithScore($company, 0);
            $this->entityManagerHelper->getEntityManager()->flush();
        }

        $this->redirect(
            'show',
            'Customer',
            'FlowGraph.Neo4j',
            ['customer' => $customer->getId()]
        );
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeRemoveRatingForACompanyAction()
    {
        /* @var $customerMappingConfiguration PropertyMappingConfiguration */
        $customerMappingConfiguration = $this->arguments['customer']->getPropertyMappingConfiguration();
        $customerMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Customer::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Customer $customer
     * @param Company $company
     * @throws StopActionException
     */
    public function removeRatingForACompanyAction(Customer $customer, Company $company)
    {
        if($customer->hasRatedCompany($company)) {
            $rating = $customer->getRatingByCompany($company);
            $this->customerRepository->remove($rating);
            $this->customerRepository->flush();
        }

        $this->redirect(
            'show',
            'Customer',
            'FlowGraph.Neo4j',
            ['customer' => $customer->getId()]
        );
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeDeleteAction()
    {
        /* @var $customerMappingConfiguration PropertyMappingConfiguration */
        $pageMappingConfiguration = $this->arguments['customer']->getPropertyMappingConfiguration();
        $pageMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Customer::class));
    }

    /**
     * @param Customer $customer
     * @throws StopActionException
     */
    public function deleteAction(Customer $customer)
    {
        $this->customerRepository->remove($customer);
        $this->customerRepository->flush();

        $this->addFlashMessage(
            'Delete customer ' . $customer->getName() . ' successfully'
        );
        $this->redirect('list');
    }
}
