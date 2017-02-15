<?php
namespace McShop\ShoppingCartBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Storefront
 *
 * @ORM\Table(name="shopcart_item")
 * @ORM\Entity(repositoryClass="McShop\ShoppingCartBundle\Repository\ShoppingCartItemRepository")
 */
class ShoppingCartItem
{
    /** Item types */
    const TYPE_ITEM = "item";
    const TYPE_MONEY = "money";
    const TYPE_RGOWN = "rgown";
    const TYPE_RGMEM = "rgmem";
    const TYPE_PERMGROUP = "permgroup";
    const TYPE_PERM = "perm";

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type;

    /**
     * @var string
     *
     * @ORM\Column(name="item", type="string", length=255)
     */
    private $item;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var float
     *
     * @ORM\Column(name="price", type="float")
     */
    private $price;

    /**
     * @var int
     *
     * @ORM\Column(name="sale", type="integer")
     */
    private $sale;

    /**
     * @var int
     *
     * @ORM\ManyToOne(targetEntity="McShop\ServersBundle\Entity\Server")
     * @ORM\JoinColumn(nullable=true)
     */
    private $server;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @var ShoppingCartCategory
     *
     * @ORM\ManyToOne(targetEntity="McShop\ShoppingCartBundle\Entity\ShoppingCartCategory", inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="extra", type="string", length=255, nullable=true)
     */
    private $extra;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return ShoppingCartItem
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
     * Set type
     *
     * @param string $type
     *
     * @return ShoppingCartItem
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set item
     *
     * @param string $item
     *
     * @return ShoppingCartItem
     */
    public function setItem($item)
    {
        $this->item = $item;

        return $this;
    }

    /**
     * Get item
     *
     * @return string
     */
    public function getItem()
    {
        return $this->item;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     *
     * @return ShoppingCartItem
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * Set price
     *
     * @param float $price
     *
     * @return ShoppingCartItem
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set sale
     *
     * @param integer $sale
     *
     * @return ShoppingCartItem
     */
    public function setSale($sale)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return integer
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return ShoppingCartItem
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set server
     *
     * @param \McShop\ServersBundle\Entity\Server $server
     *
     * @return ShoppingCartItem
     */
    public function setServer(\McShop\ServersBundle\Entity\Server $server = null)
    {
        $this->server = $server;

        return $this;
    }

    /**
     * Get server
     *
     * @return \McShop\ServersBundle\Entity\Server
     */
    public function getServer()
    {
        return $this->server;
    }

    /**
     * Set category
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $category
     *
     * @return ShoppingCartItem
     */
    public function setCategory(\McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $category = null)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return \McShop\ShoppingCartBundle\Entity\ShoppingCartCategory
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set extra
     *
     * @param string $extra
     *
     * @return ShoppingCartItem
     */
    public function setExtra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * Get extra
     *
     * @return string
     */
    public function getExtra()
    {
        return $this->extra;
    }

    /**
     * @return array
     */
    public static function getTypes()
    {
        $prefix = 'shopping_cart.item.type.';

        return [
            self::TYPE_ITEM         => $prefix . self::TYPE_ITEM,
            self::TYPE_MONEY        => $prefix . self::TYPE_MONEY,
            self::TYPE_RGOWN        => $prefix . self::TYPE_RGOWN,
            self::TYPE_RGMEM        => $prefix . self::TYPE_RGMEM,
            self::TYPE_PERMGROUP    => $prefix . self::TYPE_PERMGROUP,
            self::TYPE_PERM         => $prefix . self::TYPE_PERM,
        ];
    }
}
