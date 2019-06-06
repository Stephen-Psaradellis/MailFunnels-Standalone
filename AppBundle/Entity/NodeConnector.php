<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NodeConnector
 *
 * @ORM\Table(name="node_connector")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NodeConnectorRepository")
 */
class NodeConnector
{

    /* ----- GLOBAL START NODE VALUES ----- */
    public static $NOT_START_NODE = 0;
    public static $START_NODE = 1;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;


    /**
     * @var Node
     *
     * ORM\ManyToOne(targetEntity="AppBundle\Entity\Node")
     */
    private $headNode;

    /**
     * @var Node
     *
     * ORM\ManyToOne(targetEntity="AppBundle\Entity\Node")
     */
    private $tailNode;

    /**
     * @var int
     *
     */
    private $isStartNode;

    /**
     * @var Funnel
     *
     * ORM\ManyToOne(targetEntity="AppBundle\Entity\Funnel")
     */
    private $funnel;

    /**
     * NodeConnector constructor.
     * @param Node|null $headNode
     * @param Node $tailNode
     * @param Funnel $funnel
     */
    public function __construct(Node $headNode = null, Node $tailNode, Funnel $funnel)
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
        $this->headNode = $headNode;
        $this->tailNode = $tailNode;
        $this->funnel = $funnel;
    }


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCreated.
     *
     * @param \DateTime $dateCreated
     *
     * @return NodeConnector
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated.
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated.
     *
     * @param \DateTime $dateUpdated
     *
     * @return NodeConnector
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated.
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @return Node
     */
    public function getHeadNode()
    {
        return $this->headNode;
    }

    /**
     * @param Node $headNode
     */
    public function setHeadNode($headNode)
    {
        $this->headNode = $headNode;
    }

    /**
     * @return Node
     */
    public function getTailNode()
    {
        return $this->tailNode;
    }

    /**
     * @param Node $tailNode
     */
    public function setTailNode($tailNode)
    {
        $this->tailNode = $tailNode;
    }

    /**
     * @return int
     */
    public function getisStartNode()
    {
        return $this->isStartNode;
    }

    /**
     * @param int $isStartNode
     */
    public function setIsStartNode($isStartNode)
    {
        $this->isStartNode = $isStartNode;
    }

    /**
     * @return Funnel
     */
    public function getFunnel()
    {
        return $this->funnel;
    }

    /**
     * @param Funnel $funnel
     */
    public function setFunnel($funnel)
    {
        $this->funnel = $funnel;
    }
}
