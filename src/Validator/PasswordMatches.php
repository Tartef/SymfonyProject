<?php
// src/Validator/PasswordMatches.php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordMatches extends Constraint
{
    public $message = 'Le mot de passe ne correspond pas à la confirmation.';
}
