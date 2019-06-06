<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;


/**
 * Node
 *
 * @ORM\Table(name="node")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NodeRepository")
 */
class Node
{

    /* --- GLOBAL DELAY UNIT VALUES --- */
    public static $SECONDS = 0;
    public static $MINUTES = 1;
    public static $HOURS = 2;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var int
     *
     * @ORM\Column(name="numEmails", type="integer")
     */
    private $numEmails;

    /**
     * @var string
     *
     * @ORM\Column(name="numEmailsSent", type="string", length=255)
     */
    private $numEmailsSent;

    /**
     * @var int
     *
     * @ORM\Column(name="numEmailsOpened", type="integer")
     */
    private $numEmailsOpened;

    /**
     * @var int
     *
     * @ORM\Column(name="numEmailsClicked", type="integer")
     */
    private $numEmailsClicked;

    /**
     * @var float
     *
     * @ORM\Column(name="revenue", type="float")
     */
    private $revenue;

    /**
     * @var int
     *
     * @ORM\Column(name="delayTime", type="integer")
     */
    private $delayTime;

    /**
     * @var int
     *
     * @ORM\Column(name="delayUnit", type="integer")
     */
    private $delayUnit;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateCreated", type="datetime")
     */
    private $dateCreated;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;

    public function __construct()
    {
        $this->dateCreated = new DateTime();
        $this->dateUpdated = new DateTime();
    }


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Node
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set numEmails
     *
     * @param integer $numEmails
     *
     * @return Node
     */
    public function setNumEmails($numEmails)
    {
        $this->numEmails = $numEmails;

        return $this;
    }

    /**
     * Get numEmails
     *
     * @return int
     */
    public function getNumEmails()
    {
        return $this->numEmails;
    }

    /**
     * Set numEmailsSent
     *
     * @param string $numEmailsSent
     *
     * @return Node
     */
    public function setNumEmailsSent($numEmailsSent)
    {
        $this->numEmailsSent = $numEmailsSent;

        return $this;
    }

    /**
     * Get numEmailsSent
     *
     * @return string
     */
    public function getNumEmailsSent()
    {
        return $this->numEmailsSent;
    }

    /**
     * Set numEmailsOpened
     *
     * @param integer $numEmailsOpened
     *
     * @return Node
     */
    public function setNumEmailsOpened($numEmailsOpened)
    {
        $this->numEmailsOpened = $numEmailsOpened;

        return $this;
    }

    /**
     * Get numEmailsOpened
     *
     * @return int
     */
    public function getNumEmailsOpened()
    {
        return $this->numEmailsOpened;
    }

    /**
     * Set numEmailsClicked
     *
     * @param integer $numEmailsClicked
     *
     * @return Node
     */
    public function setNumEmailsClicked($numEmailsClicked)
    {
        $this->numEmailsClicked = $numEmailsClicked;

        return $this;
    }

    /**
     * Get numEmailsClicked
     *
     * @return int
     */
    public function getNumEmailsClicked()
    {
        return $this->numEmailsClicked;
    }

    /**
     * Set revenue
     *
     * @param float $revenue
     *
     * @return Node
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get revenue
     *
     * @return float
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * Set delayTime
     *
     * @param integer $delayTime
     *
     * @return Node
     */
    public function setDelayTime($delayTime)
    {
        $this->delayTime = $delayTime;

        return $this;
    }

    /**
     * Get delayTime
     *
     * @return int
     */
    public function getDelayTime()
    {
        return $this->delayTime;
    }

    /**
     * Set delayUnit
     *
     * @param integer $delayUnit
     *
     * @return Node
     */
    public function setDelayUnit($delayUnit)
    {
        $this->delayUnit = $delayUnit;

        return $this;
    }

    /**
     * Get delayUnit
     *
     * @return int
     */
    public function getDelayUnit()
    {
        if($this->delayUnit == '0')
            return 'Seconds';
        elseif($this->delayUnit == '1')
            return 'Minutes';
        elseif($this->delayUnit == '2')
            return 'Hours';
    }

    /**
     * Set dateCreated
     *
     * @param DateTime $dateCreated
     *
     * @return Node
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param DateTime $dateUpdated
     *
     * @return Node
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }
}

