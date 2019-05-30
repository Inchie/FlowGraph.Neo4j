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
 * @OGM\Node(label="Employee")
 */
class Employee
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
     * @var int
     *
     *  @OGM\Property(type="int")
     */
    protected $born;

    /**
     * @var Company[]|Collection
     *
     * @OGM\Relationship(type="WORKED_FOR", direction="OUTGOING", collection=true, mappedBy="employees", targetEntity="Company")
     */
    protected $companies;

    /**
     * Person constructor.
     */
    public function __construct()
    {
        $this->companies = new Collection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return int
     */
    public function getBorn()
    {
        return $this->born;
    }

    /**
     * @param int $born
     */
    public function setBorn($born)
    {
        $this->born = $born;
    }

    /**
     * @return Company[]|Collection
     */
    public function getCompanies()
    {
        return $this->companies;
    }

    /**
     * @param Company $company
     */
    public function addCompany(Company $company)
    {
        if(!$this->companies->contains($company)) {
            $this->companies->add($company);
        }
    }
}
