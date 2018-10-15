<?php
namespace McShop\SettingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexxpavlov\SpellingBundle\Entity\Spelling as BaseSpelling;
use McShop\UserBundle\Entity\User;

/**
 * @ORM\Table(name="spelling")
 * @ORM\Entity
 */
class Spelling extends BaseSpelling
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="McShop\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="creator_id", referencedColumnName="id")
     */
    protected $creator;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="McShop\UserBundle\Entity\User")
     * @ORM\JoinColumn(name="corrector_id", referencedColumnName="id")
     */
    protected $corrector;

    /**
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
