<?php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{

    public static $SHOPIFY_INTEGRATED_NO = 0;
    public static $SHOPIFY_INTEGRATED_YES = 1;

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;


    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", nullable=false)
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", nullable=false)
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="defaultHtml", type="text", nullable=true)
     */
    private $defaultHtml;

    /* --- Shopify URL Info --- */

    /**
     * @var int
     *
     * @ORM\Column(name="shopify_integration", type="integer")
     */
    private $shopifyIntegration;

    /**
     * @var string
     *
     * @ORM\Column(name="shopify_store", type="text", nullable=true)
     */
    private $shopifyStore;

    /**
     * @var string
     *
     * @ORM\Column(name="shopify_token", type="text", nullable=true)
     */
    private $shopifyToken;


    /* --- OneToMany Relationships --- */

    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\UserIntegrationLink", mappedBy="user")
     */
    private $integrations;




    public function __construct()
    {
        $this->shopifyIntegration = $this::$SHOPIFY_INTEGRATED_NO;
        parent::__construct();
        // your own logic
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }


    /**
     * @return string
     */
    public function getDefaultHtml()
    {
        return $this->defaultHtml;
    }

    /**
     * @param string $defaultHtml
     */
    public function setDefaultHtml($defaultHtml)
    {
        $this->defaultHtml = $defaultHtml;
    }

    /**
     * @return string
     */
    public function getShopifyStore()
    {
        return $this->shopifyStore;
    }

    /**
     * @param string $shopifyStore
     */
    public function setShopifyStore($shopifyStore)
    {
        $this->shopifyStore = $shopifyStore;
    }

    /**
     * @return string
     */
    public function getShopifyToken()
    {
        return $this->shopifyToken;
    }

    /**
     * @param string $shopifyToken
     */
    public function setShopifyToken($shopifyToken)
    {
        $this->shopifyToken = $shopifyToken;
    }

    /**
     * @return int
     */
    public function getShopifyIntegration()
    {
        return $this->shopifyIntegration;
    }

    /**
     * @param int $shopifyIntegration
     */
    public function setShopifyIntegration($shopifyIntegration)
    {
        $this->shopifyIntegration = $shopifyIntegration;
    }

    /**
     * @return mixed
     */
    public function getIntegrations()
    {
        return $this->integrations;
    }


    /* --- HELPER FUNCTIONS --- */
    /* ------------------------ */

    public function getFullName() {
        return $this->getFirstName()." ".$this->getLastName();
    }




}