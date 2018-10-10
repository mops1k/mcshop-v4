<?php
namespace McShop\SettingBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Lexxpavlov\SpellingBundle\Entity\SpellingSecurity as BaseSpellingSecurity;

/**
 * @ORM\Table(name="spelling_security")
 * @ORM\Entity
 */
class SpellingSecurity extends BaseSpellingSecurity
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
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
