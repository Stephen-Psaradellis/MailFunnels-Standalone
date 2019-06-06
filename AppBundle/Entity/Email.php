<?php

namespace AppBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * Email
 *
 * @ORM\Table(name="email")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmailRepository")
 */
class Email
{

    /* --- GLOBAL STATUS VARIABLES --- */
    public static $STATUS_CANCELLED = -2;
    public static $STATUS_ERROR = -1;
    public static $STATUS_PENDING = 0;
    public static $STATUS_SUCCESS = 1;

    /* --- GLOBAL OPENED VALUES --- */
    public static $OPENED_NO = 0;
    public static $OPENED_YES = 1;

    /* --- GLOBAL CLICKED VALUES --- */
    public static $CLICKED_NO = 0;
    public static $CLICKED_YES = 1;

    /* --- EMAIL TYPE --- */
    public static $TYPE_BASE = 0;
    public static $TYPE_POSTMARK = 1;
    public static $TYPE_SENDGRID = 2;


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
     * @ORM\Column(name="postmarkID", type="string", length=255, nullable=true)
     */
    private $postmarkID;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;

    /**
     * @var int
     *
     * @ORM\Column(name="opened", type="integer")
     */
    private $opened;

    /**
     * @var int
     *
     * @ORM\Column(name="clicked", type="integer")
     */
    private $clicked;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="string", length=255, nullable=true)
     */
    private $html;

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

    /**
     * @var integer
     *
     * @ORM\Column(name="type", type="integer")
     */
    private $type;


    /**
     * @var integer
     *
     * @ORM\Column(name="broadcastID", type="integer", nullable=true)
     */
    private $broadcastID;

    /**
     * @var integer
     *
     * @ORM\Column(name="subscriberID", type="integer", nullable=true)
     */
    private $subscriberID;



    /* ---- ManyToOne Relationships --- */

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;


    public function __construct(User $user, $type=null, $postmarkID = null)
    {
        $this->dateCreated = new DateTime();
        $this->dateUpdated = new DateTime();
        $this->user = $user;
        $this->postmarkID = $postmarkID;
        $this->clicked = Email::$CLICKED_NO;
        $this->opened = Email::$OPENED_NO;
        $this->status = Email::$STATUS_PENDING;
        if(!$type) {
            $this->type = Email::$TYPE_BASE;
        }
        else {
            $this->type = $type;
        }
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
     * Set postmarkID.
     *
     * @param string $postmarkID
     *
     * @return Email
     */
    public function setPostmarkID($postmarkID)
    {
        $this->postmarkID = $postmarkID;

        return $this;
    }

    /**
     * Get postmarkID.
     *
     * @return string
     */
    public function getPostmarkID()
    {
        return $this->postmarkID;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return Email
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set opened.
     *
     * @param int $opened
     *
     * @return Email
     */
    public function setOpened($opened)
    {
        $this->opened = $opened;

        return $this;
    }

    /**
     * Get opened.
     *
     * @return int
     */
    public function getOpened()
    {
        return $this->opened;
    }

    /**
     * Set clicked.
     *
     * @param int $clicked
     *
     * @return Email
     */
    public function setClicked($clicked)
    {
        $this->clicked = $clicked;

        return $this;
    }

    /**
     * Get clicked.
     *
     * @return int
     */
    public function getClicked()
    {
        return $this->clicked;
    }

    /**
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * @param string $html
     */
    public function setHtml($html)
    {
        $this->html = $html;
    }

    /**
     * @return DateTime
     */
    public function getDateCreated()
    {
        return $this->dateCreated;
    }

    /**
     * @param DateTime $dateCreated
     */
    public function setDateCreated($dateCreated)
    {
        $this->dateCreated = $dateCreated;
    }

    /**
     * @return DateTime
     */
    public function getDateUpdated()
    {
        return $this->dateUpdated;
    }

    /**
     * @param DateTime $dateUpdated
     */
    public function setDateUpdated($dateUpdated)
    {
        $this->dateUpdated = $dateUpdated;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return int
     */
    public function getBroadcastID()
    {
        return $this->broadcastID;
    }

    /**
     * @param int $broadcastID
     */
    public function setBroadcastID($broadcastID)
    {
        $this->broadcastID = $broadcastID;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return int
     */
    public function getSubscriberID()
    {
        return $this->subscriberID;
    }

    /**
     * @param int $subscriberID
     */
    public function setSubscriberID($subscriberID)
    {
        $this->subscriberID = $subscriberID;
    }
}
