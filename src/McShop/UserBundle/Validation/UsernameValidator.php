<?php
namespace McShop\UserBundle\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UsernameValidator extends ConstraintValidator
{

    /**
     * Checks if the passed value is valid.
     *
     * @param mixed $value The value that should be validated
     * @param Constraint $constraint The constraint for the validation
     */
    public function validate($value, Constraint $constraint)
    {
        $error = false;

        if (strlen($value) > 20 || strlen($value) < 4) {
            $error = true;
        }

        if (!preg_match('/^[a-zA-Z0-9_]+$/', $value)) {
            $error = true;
        }

        if ($error) {
            $this->context->buildViolation($constraint->message)
                ->addViolation()
            ;
        }
    }
}
