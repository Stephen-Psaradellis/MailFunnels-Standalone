<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Funnel
 *
 * @ORM\Table(name="funnel")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\FunnelRepository")
 */
class Funnel
{


    /* --- GLOBAL ARCHIVED VALUES --- */
    public static $ACTIVE_NO = 0;
    public static $ACTIVE_YES = 1;

    /* -- GLOBAL HOOK VALUES -- */
    public static $TYPE_BASE = 0;
    public static $TYPE_PURCHASE = 1;
    public static $TYPE_ABANDON_CART = 2;
    public static $TYPE_REFUND = 3;


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
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

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
     * @var int
     *
     * @ORM\Column(name="active", type="integer")
     */
    private $active;


    /**
     * @var int
     *
     * @ORM\Column(name="hook", type="integer")
     */

    private $hook;



    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
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
     * @return Funnel
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
     * Set description
     *
     * @param string $description
     *
     * @return Funnel
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Funnel
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;

        return $this;
    }

    /**
     * Get dateCreated
     *
     * @return \DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * Set dateUpdated
     *
     * @param \DateTime $dateUpdated
     *
     * @return Funnel
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    /**
     * Get dateUpdated
     *
     * @return \DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * Set active
     *
     * @param integer $active
     *
     * @return Funnel
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return int
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get Hook String
     * @return string
     */
    public function getHookString()
    {
        if($this->hook == $this::$TYPE_PURCHASE)
            return 'Purchased Product';
        elseif($this->hook == $this::$TYPE_REFUND)
            return 'Refunded Product';
        elseif($this->hook == $this::$TYPE_ABANDON_CART)
            return 'Abandoned Cart';
        elseif($this->hook == $this::$TYPE_BASE)
            return 'Base';
    }

    /**
     * Get Hook Int Value
     * @return int
     */
    public function getHook()
    {
        return $this->hook;
    }

    /**
     * @param int $hook
     */
    public function setHook($hook)
    {
        $this->hook = $hook;
    }
}

