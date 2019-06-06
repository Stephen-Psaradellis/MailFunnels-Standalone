<?php

namespace AppBundle\Entity;

use DateTime;
use AppBundle\Entity\Node;
use AppBundle\Entity\EmailTemplate;
use Doctrine\ORM\Mapping as ORM;

/**
 * NodeEmailTemplateLink
 *
 * @ORM\Table(name="node_email_template_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\NodeEmailTemplateLinkRepository")
 */
class NodeEmailTemplateLink
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
     * @var int
     *
     * @ORM\Column(name="archived", type="integer")
     */
    private $archived;

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
     * @var Node
     *
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Node")
     */
    private $node;

    /**
     * @var EmailTemplate
     *
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\EmailTemplate")
     */
    private $emailTemplate;


    public function __construct(Node $node, EmailTemplate $emailTemplate)
    {
        $this->dateCreated = new DateTime();
        $this->dateUpdated = new DateTime();
        $this->archived = $this::$ARCHIVED_NO;
        $this->node = $node;
        $this->emailTemplate = $emailTemplate;
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
     * Set archived
     *
     * @param integer $archived
     *
     * @return NodeEmailTemplateLink
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
     * Set dateCreated
     *
     * @param DateTime $dateCreated
     *
     * @return NodeEmailTemplateLink
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
     * @return NodeEmailTemplateLink
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

