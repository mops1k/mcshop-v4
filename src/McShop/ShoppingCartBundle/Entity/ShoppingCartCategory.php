<?php

namespace McShop\ShoppingCartBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Tree\Traits\NestedSetEntity;

/**
 * ShoppingCartCategory
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="shopcart_category")
 * @ORM\Entity(repositoryClass="McShop\ShoppingCartBundle\Repository\ShoppingCartCategoryRepository")
 */
class ShoppingCartCategory
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
     * @ORM\ManyToOne(targetEntity="ShoppingCartCategory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="ShoppingCartCategory", mappedBy="parent")
     * @ORM\OrderBy({"left" = "ASC"})
     */
    private $children;

    /**
     * @var ShoppingCartItem[]
     * @ORM\OneToMany(targetEntity="McShop\ShoppingCartBundle\Entity\ShoppingCartItem", mappedBy="category")
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
     * @param ShoppingCartCategory|null $parent
     */
    public function setParent(ShoppingCartCategory $parent = null)
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
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
        $this->items = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add child
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $child
     *
     * @return ShoppingCartCategory
     */
    public function addChild(\McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $child
     */
    public function removeChild(\McShop\ShoppingCartBundle\Entity\ShoppingCartCategory $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return ShoppingCartCategory[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add item
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item
     *
     * @return ShoppingCartCategory
     */
    public function addItem(\McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item)
    {
        $this->items[] = $item;

        return $this;
    }

    /**
     * Remove item
     *
     * @param \McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item
     */
    public function removeItem(\McShop\ShoppingCartBundle\Entity\ShoppingCartItem $item)
    {
        $this->items->removeElement($item);
    }

    /**
     * Get items
     *
     * @return ShoppingCartItem[]
     */
    public function getItems()
    {
        return $this->items;
    }
}
