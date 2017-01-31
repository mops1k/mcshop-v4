<?php

namespace McShop\NewsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use McShop\UserBundle\Entity\User;

/**
 * Commentary
 *
 * @ORM\Table(name="commentary")
 * @ORM\Entity(repositoryClass="McShop\NewsBundle\Repository\CommentaryRepository")
 */
class Commentary
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
     * @var string
     *
     * @ORM\Column(name="content", type="text")
     */
    private $content;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="McShop\UserBundle\Entity\User")
     */
    private $user;

    /**
     * @var Post
     * @ORM\ManyToOne(targetEntity="Post", inversedBy="commentaries")
     */
    private $news;

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
     * Set content
     *
     * @param string $content
     * @return Commentary
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string 
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set user
     *
     * @param \McShop\NewsBundle\Entity\User $user
     * @return Commentary
     */
    public function setUser(\McShop\NewsBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \McShop\NewsBundle\Entity\User 
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set news
     *
     * @param \McShop\NewsBundle\Entity\Post $news
     * @return Commentary
     */
    public function setNews(\McShop\NewsBundle\Entity\Post $news = null)
    {
        $this->news = $news;

        return $this;
    }

    /**
     * Get news
     *
     * @return \McShop\NewsBundle\Entity\Post 
     */
    public function getNews()
    {
        return $this->news;
    }
}
