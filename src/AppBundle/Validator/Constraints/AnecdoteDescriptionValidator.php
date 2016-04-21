<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class AnecdoteDescriptionValidator extends ConstraintValidator
{
    private $regexp;

    public function __construct($regexp)
    {
        $this->regexp = $regexp;
    }

    public function validate($value, Constraint $constraint)
    {
        if ($this->regexp != null) {
            if (!preg_match($this->regexp, $value, $matches)) {
                $this->context->buildViolation($constraint->message)
                  ->setParameter('%regexp%', $this->regexp)
                  ->addViolation();
            }
        }
    }
}