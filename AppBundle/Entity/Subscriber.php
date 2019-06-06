<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Subscriber
 *
 * @ORM\Table(name="subscriber")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberRepository")
 */
class Subscriber
{
    /* --- GLOBAL REFERENCE TYPE VALUES --- */
    public static $REF_TYPE_MANUAL = 0;
    public static $REF_TYPE_PURCHASE = 1;
    public static $REF_TYPE_ABANDON_CART = 2;
    public static $REF_TYPE_REFUND = 3;

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
     * @ORM\Column(name="firstName", type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="lastName", type="string", length=255)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255)
     */
    private $email;

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
     * @ORM\Column(name="revenue", type="integer")
     */
    private $revenue;

    /**
     * @var int
     *
     * @ORM\Column(name="refType", type="integer")
     */
    private $refType;

    /**
     * @var string
     *
     * @ORM\Column(name="abandonedURL", type="string", length=255, nullable=true)
     */
    private $abandonedURL;


    public function __construct()
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
        $this->revenue = 0;
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
     * Set firstName
     *
     * @param string $firstName
     *
     * @return Subscriber
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get firstName
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set lastName
     *
     * @param string $lastName
     *
     * @return Subscriber
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get lastName
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Subscriber
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return Subscriber
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
     * @return Subscriber
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
     * Set revenue
     *
     * @param integer $revenue
     *
     * @return Subscriber
     */
    public function setRevenue($revenue)
    {
        $this->revenue = $revenue;

        return $this;
    }

    /**
     * Get revenue
     *
     * @return int
     */
    public function getRevenue()
    {
        return $this->revenue;
    }

    /**
     * Set refType
     *
     * @param integer $refType
     *
     * @return Subscriber
     */
    public function setRefType($refType)
    {
        $this->refType = $refType;

        return $this;
    }

    /**
     * Get refType
     *
     * @return int
     */
    public function getRefType() {
        if($this->refType == $this::$REF_TYPE_MANUAL)
            return 'Manual';
        elseif($this->refType == $this::$REF_TYPE_ABANDON_CART)
            return 'Abandoned Cart';
        elseif($this->refType == $this::$REF_TYPE_PURCHASE)
            return 'Purchased Product';
        elseif($this->refType == $this::$REF_TYPE_REFUND)
            return 'Refunded Product';
    }

    /**
     * Set abandonedURL
     *
     * @param string $abandonedURL
     *
     * @return Subscriber
     */
    public function setAbandonedURL($abandonedURL)
    {
        $this->abandonedURL = $abandonedURL;

        return $this;
    }

    /**
     * Get abandonedURL
     *
     * @return string
     */
    public function getAbandonedURL()
    {
        return $this->abandonedURL;
    }
}

