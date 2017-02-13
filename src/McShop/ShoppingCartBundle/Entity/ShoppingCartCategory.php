<?php

namespace McShop\ShoppingCartBundle\Entity;

use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Tree\Traits\NestedSetEntity;

/**
 * ShoppingCartCategory
 *
 * @Gedmo\Tree(type="nested")
 * @ORM\Table(name="shopping_cart_category")
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
     * @ORM\OrderBy({"lft" = "ASC"})
     */
    private $children;

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
}

