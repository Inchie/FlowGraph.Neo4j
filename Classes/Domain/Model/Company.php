<?php
namespace FlowGraph\Neo4j\Domain\Model;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphAware\Neo4j\OGM\Annotations as OGM;
use GraphAware\Neo4j\OGM\Common\Collection;

/**
 * @OGM\Node(label="Company")
 */
class Company
{
    /**
     * @var int
     *
     * @OGM\GraphId()
     */
    protected $id;

    /**
     * @var string
     *
     * @OGM\Property(type="string")
     */
    protected $name;

    /**
     * @var string
     *
     * @OGM\Property(type="string")
     */
    protected $city;

    /**
     * @var Employee[]|Collection
     *
     * @OGM\Relationship(type="WORKED_FOR", direction="INCOMING", collection=true, mappedBy="companies", targetEntity="Employee")
     */
    protected $employees;

    /**
     * @var CompanyRating[]
     *
     * @OGM\Relationship(relationshipEntity="CompanyRating", type="RATED", direction="INCOMING", collection=true, mappedBy="company")
     */
    protected $ratings;

    /**
     * Company constructor.
     */
    public function __construct()
    {
        $this->employees = new Collection();
        $this->ratings = new Collection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $city
     */
    public function setCity($city)
    {
        $this->city = $city;
    }

    /**
     * @return Employee[]|Collection
     */
    public function getEmployees()
    {
        return $this->employees;
    }

    /**
     * @param Employee $employee
     */
    public function addEmployee(Employee $employee)
    {
        if(!$this->employees->contains($employee)) {
            $this->employees->add($employee);
        }
    }

    /**
     * @param $ratings
     */
    public function setRatings($ratings)
    {
        $this->ratings = $ratings;
    }

    /**
     * @return Collection
     */
    public function getRatings()
    {
        return $this->ratings;
    }
}

