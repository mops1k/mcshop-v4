<?php

namespace McShop\ShopBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use McShop\ShopBundle\Interfaces\ProductHandlerInterface;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\SoftDeleteable\Traits\SoftDeleteableEntity;

/**
 * Item
 *
 * @ORM\Table(name="item")
 * @ORM\Entity(repositoryClass="McShop\ShopBundle\Repository\ItemRepository")
 * @Gedmo\SoftDeleteable(fieldName="deletedAt")
 */
class Item
{
    use SoftDeleteableEntity, TimestampableEntity;

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
     * @ORM\Column(name="name", type="string", length=255, unique=true)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="price", type="integer")
     */
    private $price;

    /**
     * @var int|null
     *
     * @ORM\Column(name="discount", type="integer", nullable=true)
     */
    private $discount;

    /**
     * @var int
     *
     * @ORM\Column(name="amount", type="integer")
     */
    private $amount;

    /**
     * @var array|null
     *
     * @ORM\Column(name="additional_fields", type="json_array", nullable=true)
     */
    private $additionalFields;

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
     * @var ItemCategory
     *
     * @ORM\ManyToOne(targetEntity="McShop\ShopBundle\Entity\ItemCategory", inversedBy="items")
     * @ORM\JoinColumn(nullable=true)
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="handler_name", type="string", length=255)
     */
    private $handlerName;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Item
     */
    public function setName($name): Item
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return Item
     */
    public function setDescription(?string $description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set price.
     *
     * @param float $price
     *
     * @return Item
     */
    public function setPrice(float $price): Item
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price.
     *
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @return int|null
     */
    public function getDiscount(): ?int
    {
        return $this->discount;
    }

    /**
     * @param int|null $discount
     */
    public function setDiscount(?int $discount): void
    {
        $this->discount = $discount;
    }

    /**
     * Set amount.
     *
     * @param int $amount
     *
     * @return Item
     */
    public function setAmount($amount): Item
    {
        $this->amount = $amount;

        return $this;
    }

    /**
     * Get amount.
     *
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * Set extra.
     *
     * @param array|null $additionalFields
     *
     * @return Item
     */
    public function setAdditionalFields($additionalFields = null): Item
    {
        $this->additionalFields = $additionalFields;

        return $this;
    }

    /**
     * Get extra.
     *
     * @return array|null
     */
    public function getAdditionalFields(): ?array
    {
        return $this->additionalFields;
    }

    /**
     * @return string
     */
    public function getHandlerName(): string
    {
        return $this->handlerName;
    }

    /**
     * @param ProductHandlerInterface $handler
     * @return Item
     */
    public function setHandlerName(ProductHandlerInterface $handler): Item
    {
        $this->handlerName = $handler->getName();

        return $this;
    }

    /**
     * @return int
     */
    public function getServer(): int
    {
        return $this->server;
    }

    /**
     * @param int $server
     */
    public function setServer(int $server): void
    {
        $this->server = $server;
    }

    /**
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image): void
    {
        $this->image = $image;
    }

    /**
     * @return ItemCategory|null
     */
    public function getCategory(): ?ItemCategory
    {
        return $this->category;
    }

    /**
     * @param ItemCategory|null $category
     */
    public function setCategory(?ItemCategory $category): void
    {
        $this->category = $category;
    }
}
