<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailListUserLink
 *
 * @ORM\Table(name="email_list_user_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmailListUserLinkRepository")
 */
class EmailListUserLink
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
     *
     */
    private $emailList;


    /**
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;



    public function __construct(User $user, EmailList $emailList) {
        $this->dateCreated = new \DateTime();
        $this->archived = $this::$ARCHIVED_NO;
        $this->user = $user;
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
     * @return EmailListUserLink
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
     * Set archived
     *
     * @param integer $archived
     *
     * @return EmailListUserLink
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived
     *
     * @return int
     */
    public function getArchived()
    {
        return $this->archived;
    }

    /**
     * @return mixed
     */
    public function getEmailList()
    {
        return $this->emailList;
    }

    /**
     * @param mixed $emailList
     */
    public function setEmailList($emailList)
    {
        $this->emailList = $emailList;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }
}

