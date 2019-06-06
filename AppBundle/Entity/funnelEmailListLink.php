<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * funnelEmailListLink
 *
 * @ORM\Table(name="funnel_email_list_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\funnelEmailListLinkRepository")
 */
class funnelEmailListLink
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
     * @var Funnel
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Funnel")
     *
     */
    private $funnel;


    /**
     *
     * @var EmailList
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EmailList")
     */
    private $emailList;



    public function __construct(Funnel $funnel, EmailList $emailList) {
        $this->dateCreated = new \DateTime();
        $this->archived = $this::$ARCHIVED_NO;
        $this->funnel = $funnel;
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
     * @return funnelEmailListLink
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
     * @return funnelEmailListLink
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
}

