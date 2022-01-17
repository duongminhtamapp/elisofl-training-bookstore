<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class InternationalPhone extends Constraint
{
    public $message = 'Phone is invalid';
    public $mode = 'strict'; // If the constraint has configuration options, define them as public properties

    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}