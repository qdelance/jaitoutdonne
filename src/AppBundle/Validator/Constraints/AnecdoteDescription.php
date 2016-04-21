<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class AnecdoteDescription extends Constraint
{
    public $message = 'constraint.anecdote.description.error';

    public function validatedBy()
    {
        return 'validator.anecdote.description';
    }
}