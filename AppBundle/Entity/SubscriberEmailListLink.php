<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * SubscriberEmailListLink
 *
 * @ORM\Table(name="subscriber_email_list_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\SubscriberEmailListLinkRepository")
 */
class SubscriberEmailListLink
{


    /* --- GLOBAL ARCHIVED VALUES --- */
    public static $ARCHIVED_NO = 0;
    public static $ARCHIVED_YES = 1;


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
     * @var int
     *
     * @ORM\Column(name="archived", type="integer")
     */
    private $archived;


    /**
     * @var EmailList
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EmailList")
     */

    private $emailList;


    /**
     * @var Subscriber
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Subscriber")
     */

    private $subscriber;

    public function __construct(Subscriber $subscriber, EmailList $emailList) {
        $this->dateCreated = new \DateTime();
        $this->archived = $this::$ARCHIVED_NO;
        $this->subscriber = $subscriber;
        $this->emailList = $emailList;
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
     * Set dateCreated
     *
     * @param \DateTime $dateCreated
     *
     * @return SubscriberEmailListLink
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
     * @return EmailList
     */
    public function getEmailList()
    {
        return $this->emailList;
    }

    /**
     * @param EmailList $emailList
     */
    public function setEmailList($emailList)
    {
        $this->emailList = $emailList;
    }

    /**
     * @return Subscriber
     */
    public function getSubscriber()
    {
        return $this->subscriber;
    }

    /**
     * @param Subscriber $subscriber
     */
    public function setSubscriber($subscriber)
    {
        $this->subscriber = $subscriber;
    }

    /**
     * @return int
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @param int $archived
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;
    }
}

