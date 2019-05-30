<?php
namespace FlowGraph\Neo4j\Controller;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use FlowGraph\Neo4j\Domain\Model\Company;
use FlowGraph\Neo4j\Domain\Model\Employee;
use FlowGraph\Neo4j\Domain\Repository\CompanyRepository;
use FlowGraph\Neo4j\Converter\PersistentGraphObjectConverter;
use FlowGraph\Neo4j\Domain\Repository\EmployeeRepository;
use FlowGraph\Neo4j\Persistence\EntityManagerHelper;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Mvc\Exception\StopActionException;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Property\PropertyMappingConfiguration;


/**
 * Class EmployeeController
 * @package FlowGraph\Neo4j\Controller
 */
class EmployeeController extends ActionController
{
    /**
     * @Flow\Inject
     * @var EmployeeRepository
     */
    protected $employeeRepository;

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
     * @param Employee $newEmployee
     * @throws \Exception
     */
    public function createAction(Employee $newEmployee)
    {
        $this->employeeRepository->persist($newEmployee);
        $this->employeeRepository->flush();

        $this->addFlashMessage(
            'Create employee ' . $newEmployee->getName() . ' successfully'
        );
        $this->redirect('list');
    }

    /**
     * list action
     */
    public function listAction()
    {
        $this->view->assign('employees', $this->employeeRepository->findAll())
            ->assign('newEmployee', new Employee());
    }

    /**
     * @throws \Neos\Flow\ObjectManagement\Exception\UnresolvedDependenciesException
     */
    public function initializeShowAction()
    {
        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));
    }

    /**
     * @param Employee $employee
     */
    public function showAction(Employee $employee)
    {
        $this->view->assign('employee', $employee)
            ->assign('companies', $this->companyRepository->findAll());
    }

    /**
     * initialize add company action
     */
    public function initializeAddCompanyAction()
    {
        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Employee $employee
     * @param Company $company
     * @throws StopActionException
     */
    public function addCompanyAction(Employee $employee, Company $company)
    {
        $employee->addCompany($company);
        $company->addEmployee($employee);

        $this->entityManagerHelper->getEntityManager()->flush();

        $this->addFlashMessage(
            $employee->getName() . ' worked for ' . $company->getName()
        );
        $this->redirect(
            'show',
            'Employee',
            'FlowGraph.Neo4j',
            ['employee' => $employee->getId()]
        );
    }

    /**
     * initialize remove company action
     */
    public function initializeRemoveCompanyAction()
    {
        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));

        /* @var $companyMappingConfiguration PropertyMappingConfiguration */
        $companyMappingConfiguration = $this->arguments['company']->getPropertyMappingConfiguration();
        $companyMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Company::class));
    }

    /**
     * @param Employee $employee
     * @param Company $company
     * @throws StopActionException
     */
    public function removeCompanyAction(Employee $employee, Company $company)
    {
        $employee->getCompanies()->removeElement($company);
        $company->getEmployees()->removeElement($employee);

        $this->entityManagerHelper->getEntityManager()->flush();

        $this->addFlashMessage(
            $employee->getName() . ' does not work for ' . $company->getName() . ' anymore'
        );
        $this->redirect(
            'show',
            'Employee',
            'FlowGraph.Neo4j',
            ['employee' => $employee->getId()]
        );
    }

    /**
     * initialize add company action
     */
    public function initializeDeleteAction()
    {
        /* @var $employeeMappingConfiguration PropertyMappingConfiguration */
        $employeeMappingConfiguration = $this->arguments['employee']->getPropertyMappingConfiguration();
        $employeeMappingConfiguration->setTypeConverter(new PersistentGraphObjectConverter(Employee::class));
    }

    /**
     * @param Employee $employee
     * @throws StopActionException
     */
    public function deleteAction(Employee $employee)
    {
        $this->entityManagerHelper->getEntityManager()->remove($employee);
        $this->entityManagerHelper->getEntityManager()->flush();

        $this->addFlashMessage(
            $employee->getName() . ' was successfully removed.'
        );
        $this->redirect(
            'list',
            'Employee',
            'FlowGraph.Neo4j'
        );
    }
}
