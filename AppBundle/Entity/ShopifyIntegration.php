<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ShopifyIntegration
 *
 * @ORM\Table(name="shopify_integration")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ShopifyIntegrationRepository")
 */
class ShopifyIntegration
{
    public static $STATUS_INACTIVE = -1;
    public static $STATUS_BASE = 0;
    public static $STATUS_ACTIVE = 1;

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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="domain", type="string", length=255)
     */
    private $domain;

    /**
     * @var string
     *
     * @ORM\Column(name="access_token", type="string", length=255)
     */
    private $accessToken;

    /**
     * @var int
     *
     * @ORM\Column(name="status", type="integer")
     */
    private $status;


    public function __construct($domain, $accessToken) {
        $this->dateCreated = new \DateTime();
        $this->dateUpdated = new \DateTime();
        $this->status = $this::$STATUS_ACTIVE;
        $this->name = $domain;
        $this->domain = $domain;
        $this->accessToken = $accessToken;
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
     * @return ShopifyIntegration
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
     * @return ShopifyIntegration
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
     * Set name.
     *
     * @param string $name
     *
     * @return ShopifyIntegration
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set domain.
     *
     * @param string $domain
     *
     * @return ShopifyIntegration
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    /**
     * Get domain.
     *
     * @return string
     */
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * Set accessToken.
     *
     * @param string $accessToken
     *
     * @return ShopifyIntegration
     */
    public function setAccessToken($accessToken)
    {
        $this->accessToken = $accessToken;

        return $this;
    }

    /**
     * Get accessToken.
     *
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * Set status.
     *
     * @param int $status
     *
     * @return ShopifyIntegration
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

    /* --- HELPER FUNCTION --- */

    public function getJSON() {
        return array(
            'id'                => $this->getId(),
            'dateCreated'       => $this->getDateCreated(),
            'dateUpdated'       => $this->getDateUpdated(),
            'name'              => $this->getName(),
            'domain'            => $this->getDomain(),
            'access_token'      => $this->getAccessToken(),
        );
    }
}
