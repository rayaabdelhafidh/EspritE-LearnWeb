<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class IsOnTheHourValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {


        // Check if the value is a DateTime object
        if (!$value instanceof \DateTimeInterface) {
            return;
        }

        // Check if the hour is between 8 and 18
        $hour = (int)$value->format('H');
        if ($hour < 8 || $hour > 18) {
            $this->context->buildViolation($constraint->message)
                ->addViolation();
        }
    }
}
