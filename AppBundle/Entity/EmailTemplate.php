<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * EmailTemplate
 *
 * @ORM\Table(name="email_template")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EmailTemplateRepository")
 */
class EmailTemplate
{
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="emailSubject", type="string", length=255, nullable=true)
     */
    private $emailSubject;

    /**
     * @var string
     *
     * @ORM\Column(name="html", type="text", nullable=true)
     */
    private $html;

    /**
     * @var int
     *
     * @ORM\Column(name="style", type="integer", nullable=true)
     */
    private $style;

    /**
     * @var int
     *
     * @ORM\Column(name="archived", type="integer", nullable=true)
     */
    private $archived;

    /**
     * @var int
     *
     * @ORM\Column(name="isAbandondedCart", type="integer", nullable=true)
     */
    private $isAbandondedCart;

    /**
     * @var int
     *
     * @ORM\Column(name="isDynamic", type="integer", nullable=true)
     */
    private $isDynamic;


    public function __construct()
    {
        $this->dateCreated = new \DateTime();
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
     * @return EmailTemplate
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
     * Set name
     *
     * @param string $name
     *
     * @return EmailTemplate
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
     * @return EmailTemplate
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
     * Set emailSubject
     *
     * @param string $emailSubject
     *
     * @return EmailTemplate
     */
    public function setEmailSubject($emailSubject)
    {
        $this->emailSubject = $emailSubject;

        return $this;
    }

    /**
     * Get emailSubject
     *
     * @return string
     */
    public function getEmailSubject()
    {
        return $this->emailSubject;
    }

    /**
     * Set html
     *
     * @param string $html
     *
     * @return EmailTemplate
     */
    public function setHtml($html)
    {
        $this->html = $html;

        return $this;
    }

    /**
     * Get html
     *
     * @return string
     */
    public function getHtml()
    {
        return $this->html;
    }

    /**
     * Set style
     *
     * @param integer $style
     *
     * @return EmailTemplate
     */
    public function setStyle($style)
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get style
     *
     * @return int
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set archived
     *
     * @param integer $archived
     *
     * @return EmailTemplate
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
     * Set isAbandondedCart
     *
     * @param integer $isAbandondedCart
     *
     * @return EmailTemplate
     */
    public function setIsAbandondedCart($isAbandondedCart)
    {
        $this->isAbandondedCart = $isAbandondedCart;

        return $this;
    }

    /**
     * Get isAbandondedCart
     *
     * @return int
     */
    public function getIsAbandondedCart()
    {
        return $this->isAbandondedCart;
    }

    /**
     * Set isDynamic
     *
     * @param integer $isDynamic
     *
     * @return EmailTemplate
     */
    public function setIsDynamic($isDynamic)
    {
        $this->isDynamic = $isDynamic;

        return $this;
    }

    /**
     * Get isDynamic
     *
     * @return int
     */
    public function getIsDynamic()
    {
        return $this->isDynamic;
    }

    /**
     * Clones an EmailTemplate
     *
     * @return EmailTemplate
     */
    public function cloneTemplate()
    {
        //Constructing an EmailTemplate Entity
        $templateClone = new EmailTemplate();

        //Setting the fields of the clone to the fields of the input EmailTemplate
        $templateClone->setHtml($this->getHtml());
        $templateClone->setStyle($this->getStyle());
        $templateClone->setIsDynamic($this->getIsDynamic());
        $templateClone->setArchived($this->getArchived());
        $templateClone->setIsAbandondedCart($this->getIsAbandondedCart());

        //Returning clone
        return $templateClone;

    }
}

