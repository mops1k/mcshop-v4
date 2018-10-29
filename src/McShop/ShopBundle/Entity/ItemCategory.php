<?php

namespace McShop\ShopBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Tree\Traits\NestedSetEntity;

/**
 * ShoppingCartCategory
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="item_category")
 * @ORM\Entity(repositoryClass="McShop\ShopBundle\Repository\ItemCategoryRepository")
 */
class ItemCategory
{
    use NestedSetEntity;

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(name="title", type="string", length=64)
     */
    private $title;

    /**
     * @Gedmo\TreeParent
     * @ORM\ManyToOne(targetEntity="McShop\ShopBundle\Entity\ItemCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="McShop\ShopBundle\Entity\ItemCategory", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;

    /**
     * @var Item[]
     * @ORM\OneToMany(targetEntity="McShop\ShopBundle\Entity\Item", mappedBy="category")
     */
    private $items;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return mixed
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param ItemCategory|null $parent
     */
    public function setParent(ItemCategory $parent = null)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->items = new ArrayCollection();
    }

    /**
     * Add child
     *
     * @param ItemCategory $child
     *
     * @return self
     */
    public function addChild(ItemCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param ItemCategory $child
     */
    public function removeChild(ItemCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return ItemCategory[]|ArrayCollection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add item
     *
     * @param Item $item
     *
     * @return ItemCategory
     */
    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param Item $item
     */
    public function removeItem(Item $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return ArrayCollection|Item[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
