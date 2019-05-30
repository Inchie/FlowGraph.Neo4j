<?php
namespace FlowGraph\Neo4j\Domain\Model;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\RelationshipEntity(type="RATED")
 */
class CompanyRating
{
    /**
     * @var int
     *
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var Customer
     *
     * @OGM\StartNode(targetEntity="Customer")
     */
    protected $customer;

    /**
     * @var Company
     *
     * @OGM\EndNode(targetEntity="Company")
     */
    protected $company;

    /**
     * @var int
     *
     * @OGM\Property(type="int")
     */
    protected $score;

    /**
     * CompanyRating constructor.
     * @param Customer $customer
     * @param Company $company
     * @param $score
     */
    public function __construct(Customer $customer, Company $company, $score)
    {
        $this->customer = $customer;
        $this->company = $company;
        $this->score = $score;
    }

    /**
     * @return Customer
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * @param Customer $customer
     */
    public function setCustomer($customer)
    {
        $this->customer = $customer;
    }

    /**
     * @return Company
     */
    public function getCompany()
    {
        return $this->company;
    }

    /**
     * @param Company $company
     */
    public function setCompany($company)
    {
        $this->company = $company;
    }

    /**
     * @return float
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * @param float $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }
}
