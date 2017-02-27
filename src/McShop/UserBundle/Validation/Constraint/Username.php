<?php
namespace McShop\UserBundle\Validation\Constraint;

use Doctrine\Common\Annotations\Annotation;
use Symfony\Component\Validator\Constraint;

/**
 * Class Username
 * @package McShop\UserBundle\Validation\Constraint
 *
 * @Annotation()
 */
class Username extends Constraint
{
    public $message = 'username.wrong.rule';

    public function validatedBy()
    {
        return str_replace('\Constraint', '', get_class($this)) . 'Validator';
    }
}
