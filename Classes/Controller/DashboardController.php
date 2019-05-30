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
use FlowGraph\Neo4j\Domain\Model\Employee;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Annotations as Flow;

/**
 * Class DashboardController
 * @package FlowGraph\Neo4j\Controller
 */
class DashboardController extends ActionController
{

    /**
     * @return void
     */
    public function indexAction()
    {
        $this->view->assign('newCompany', new Company())
            ->assign('newEmployee', new Employee())
            ->assign('newCustomer', new Customer());
    }

    /**
     * @throws \Neos\Flow\Mvc\Exception\StopActionException
     */
    public function redirectAction()
    {
        $this->redirect('index');
    }
}
