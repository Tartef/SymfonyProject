<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordMatchesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\PasswordMatches */

        if (null === $value || '' === $value) {
            return;
        }

        if ($value !== $this->context->getRoot()->get('confirmPassword')->getData()) {
            $this->context->buildViolation($constraint->message)
                ->atPath('plainPassword')
                ->addViolation();
        }
    }
}
