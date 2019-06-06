<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * BroadcastEmailTemplateLink
 *
 * @ORM\Table(name="broadcast_email_template_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\BroadcastEmailTemplateLinkRepository")
 */
class BroadcastEmailTemplateLink
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
     * @var \DateTime
     *
     * @ORM\Column(name="dateUpdated", type="datetime")
     */
    private $dateUpdated;

    /**
     * @var int
     *
     * @ORM\Column(name="archived", type="integer")
     */
    private $archived;

    /**
     * @var Broadcast
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Broadcast")
     */
    private $broadcast;

    /**
     * @var EmailTemplate
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EmailTemplate")
     */
    private $emailTemplate;

    public function __construct(Broadcast $broadcast, EmailTemplate $emailTemplate)
    {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
        $this->archived = $this::$ARCHIVED_NO;
        $this->broadcast = $broadcast;
        $this->emailTemplate = $emailTemplate;
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
     * @return BroadcastEmailTemplateLink
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
     * @return BroadcastEmailTemplateLink
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
     * @return Broadcast
     */
    public function getBroadcast()
    {
        return $this->broadcast;
    }

    /**
     * @param Broadcast $broadcast
     */
    public function setBroadcast($broadcast)
    {
        $this->broadcast = $broadcast;
    }

    /**
     * @return EmailTemplate
     */
    public function getEmailTemplate()
    {
        return $this->emailTemplate;
    }

    /**
     * @param EmailTemplate $emailTemplate
     */
    public function setEmailTemplate($emailTemplate)
    {
        $this->emailTemplate = $emailTemplate;
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
