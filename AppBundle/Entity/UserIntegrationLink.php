<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserIntegrationLink
 *
 * @ORM\Table(name="user_integration_link")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserIntegrationLinkRepository")
 */
class UserIntegrationLink
{
    public static $ARCHIVED_NO = 0;
    public static $ARCHIVED_YES = 1;

    public static $TYPE_BASE = 0;
    public static $TYPE_SHOPIFY = 1;

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
     * @ORM\Column(name="type", type="integer")
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(name="archived", type="integer")
     */
    private $archived;

    /* --- ManyToOne Relationships --- */

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User", inversedBy="integrations")
     */
    private $user;

    /**
     * @var ShopifyIntegration
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ShopifyIntegration")
     */
    private $shopify;

    public function __construct(User $user, $type) {
        $this->dateCreated = new \DateTime();
        $this->user = $user;
        $this->type = $type;
        $this->archived = $this::$ARCHIVED_NO;
    }

    public static function createNewShopifyIntegration(User $user, ShopifyIntegration $shopify) {
        $integrationLink = new self($user, UserIntegrationLink::$TYPE_SHOPIFY);
        $integrationLink->setShopify($shopify);
        return $integrationLink;
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
     * @return UserIntegrationLink
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
     * Set type.
     *
     * @param int $type
     *
     * @return UserIntegrationLink
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set archived.
     *
     * @param int $archived
     *
     * @return UserIntegrationLink
     */
    public function setArchived($archived)
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get archived.
     *
     * @return int
     */
    public function getArchived()
    {
        return $this->archived;
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
     * @return ShopifyIntegration
     */
    public function getShopify()
    {
        return $this->shopify;
    }

    /**
     * @param ShopifyIntegration $shopify
     */
    public function setShopify($shopify)
    {
        $this->shopify = $shopify;
    }
}
