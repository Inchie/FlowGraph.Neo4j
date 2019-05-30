<?php
namespace FlowGraph\Neo4j\Controller;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use FlowGraph\Neo4j\Domain\Model\Company;
use FlowGraph\Neo4j\Domain\Model\CompanyRating;
use FlowGraph\Neo4j\Domain\Model\Employee;
use FlowGraph\Neo4j\Domain\Repository\CompanyRepository;
use FlowGraph\Neo4j\Converter\PersistentGraphObjectConverter;
use FlowGraph\Neo4j\Domain\Repository\EmployeeRepository;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\ObjectManagement\Exception\UnresolvedDependenciesException;
use Neos\Flow\Property\PropertyMappingConfiguration;
use Neos\Flow\Annotations as Flow;

/**
 * Class CompanyController
 * @package FlowGraph\Neo4j\Controller
 */
class CompanyController extends ActionController
{
    /**
     * @Flow\Inject
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * @Flow\Inject
     * @var EmployeeRepository
     */
    protected $employeeRepository;

    /**
     * @Flow\Inject
     * @var EntityManagerHelper
     */
    protected $entityManagerHelper;

    /**
     * @param Company $newCompany
     * @throws \Exception
     */
    public function createAction(Company $newCompany)
    {
        $this->companyRepository->persist($newCompany);
        $this->companyRepository->flush();

        $this->addFlashMessage(
            'Create company ' . $newCompany->getName() . ' successfully'
        );
        $this->redirect('list');
    }

    /**
     * list action
     */
    public function listAction()
    {
        $this->view->assign('companies', $this->companyRepository->findAll())
            ->assign('newCompany', new Company());
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeShowAction()
    {
        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Company $company
     */
    public function showAction(Company $company)
    {
        $this->view->assign('company', $company)
            ->assign('employees', $this->employeeRepository->findAll());
    }

    /**
     * initialize add employee action
     */
    public function initializeAddEmployeeAction()
    {
        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Company $company
     * @param Employee $employee
     * @throws StopActionException
     */
    public function addEmployeeAction(Company $company, Employee $employee)
    {
        $company->addEmployee($employee);
        $employee->addCompany($company);

        $this->entityManagerHelper->getEntityManager()->flush();

        $this->addFlashMessage(
            $company->getName() . ' have a new employee with the following name ' . $employee->getName()
        );
        $this->redirect(
            'show',
            'Company',
            'FlowGraph.Neo4j',
            ['company' => $company->getId()]
        );
    }

    /**
     * initialize remove employee action
     */
    public function initializeRemoveEmployeeAction()
    {
        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));

        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));
    }

    /**
     * @param Company $company
     * @param Employee $employee
     * @throws StopActionException
     */
    public function removeEmployeeAction(Company $company, Employee $employee)
    {
        $company->getEmployees()->removeElement($employee);
        $employee->getCompanies()->removeElement($company);

        $this->entityManagerHelper->getEntityManager()->flush();

        $this->addFlashMessage(
            $employee->getName() . ' does not work for ' . $company->getName() . ' anymore'
        );
        $this->redirect(
            'show',
            'Company',
            'FlowGraph.Neo4j',
            ['company' => $company->getId()]
        );
    }

    /**
     * @throws UnresolvedDependenciesException
     */
    public function initializeDeleteAction()
    {
        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Company $company
     * @throws StopActionException
     */
    public function deleteAction(Company $company)
    {
        /** @var $rating CompanyRating */
        foreach ($company->getRatings() as $rating) {
            $this->entityManagerHelper->getEntityManager()->remove($rating);
        }

        $this->companyRepository->remove($company);
        $this->companyRepository->flush();

        $this->addFlashMessage(
            'Delete company ' . $company->getName() . ' successfully'
        );
        $this->redirect('list');
    }


}
