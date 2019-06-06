<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * CapturedHook
 *
 * @ORM\Table(name="captured_hook")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CapturedHookRepository")
 */
class CapturedHook
{
    public static $TYPE_BASE = 0;
    public static $TYPE_PURCHASE = 1;
    public static $TYPE_ABANDON_CART = 2;
    public static $TYPE_REFUND = 3;

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
     * @ORM\Column(name="hookJson", type="text")
     */
    private $hookJson;

    /**
     * @var int
     *
     * @ORM\Column(name="hook", type="integer")
     */
    private $hook;

    public function __construct($hook = null, $hookJson = null)
    {
        $this->dateCreated = new \DateTime();
        if(!$hook) {
            $this->hook = CapturedHook::$TYPE_BASE;
        }
        else {
            $this->hook = $hook;
        }
        $this->hookJson = $hookJson;
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
     * @return CapturedHook
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
     * Set hookJson.
     *
     * @param string $hookJson
     *
     * @return CapturedHook
     */
    public function setHookJson($hookJson)
    {
        $this->hookJson = $hookJson;

        return $this;
    }

    /**
     * Get hookJson.
     *
     * @return string
     */
    public function getHookJson()
    {
        return $this->hookJson;
    }

    /**
     * Set hook.
     *
     * @param int $hook
     *
     * @return CapturedHook
     */
    public function setType($hook)
    {
        $this->hook = $hook;

        return $this;
    }

    /**
     * Get Hook String
     * @return string
     */
    public function getHookString()
    {
        if($this->hook == $this::$TYPE_PURCHASE)
            return 'Purchased Product';
        elseif($this->hook == $this::$TYPE_REFUND)
            return 'Refunded Product';
        elseif($this->hook == $this::$TYPE_ABANDON_CART)
            return 'Abandoned Cart';
        elseif($this->hook == $this::$TYPE_BASE)
            return 'Base';
    }

    /**
     * Get hook.
     *
     * @return int
     */
    public function getHook()
    {
        return $this->hook;
    }
}
