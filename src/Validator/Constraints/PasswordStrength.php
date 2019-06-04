<?php

namespace App\Validator\Constraints;

use App\Service\Traits\TranslatorAwareTrait;
use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class PasswordStrength extends Constraint
{
    public $message;

    public function __construct($options = null)
    {
        parent::__construct($options);
        $this->message = 'user.password.requirements';
    }
}