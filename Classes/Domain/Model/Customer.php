<?php
namespace FlowGraph\Neo4j\Domain\Model;

/**
 * FlowGraph Application
 *
 * @author     Timo Nussbaum <timo.nussbaum@gmx.de>
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

use GraphAware\Neo4j\OGM\Common\Collection;
use Neos\Flow\ObjectManagement\Exception\UnresolvedDependenciesException;
use GraphAware\Neo4j\OGM\Annotations as OGM;

/**
 * @OGM\Node(label="Customer")
 */
class Customer
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
    protected $login;

    /**
     * @var Collection
     *
     * @OGM\Relationship(relationshipEntity="CompanyRating", type="RATED", direction="OUTGOING", collection=true, mappedBy="customer")
     */
    protected $ratings;

    /**
     * Customer constructor.
     */
    public function __construct()
    {
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
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param string $login
     */
    public function setLogin($login)
    {
        $this->login = $login;
    }

    /**
     * @return Collection
     */
    public function getRatings()
    {
        return $this->ratings;
    }

    /**
     * @param Company $company
     * @return bool
     */
    public function hasRatedCompany(Company $company)
    {
        foreach($this->ratings as $rating) {
            /* @var CompanyRating $rating */
            if($rating->getCompany()->getId() === $company->getId()) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param Company $company
     * @return CompanyRating|mixed|null
     */
    public function getRatingByCompany(Company $company)
    {
        foreach($this->ratings as $rating) {
            /* @var CompanyRating $rating */
            if($rating->getCompany()->getId() === $company->getId()) {
                return $rating;
            }
        }
        return null;
    }

    /**
     * @param Company $company
     * @param integer $score
     * @throws UnresolvedDependenciesException
     */
    public function rateCompanyWithScore(Company $company, $score)
    {
        $rating = new CompanyRating($this, $company, (integer) $score);
        $this->getRatings()->add($rating);
        $company->getRatings()->add($rating);
    }
}

