<?php
namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

#[\Attribute] 
class IsOnTheHour extends Constraint
{
    public $message = 'The time must be on the hour and between 08:00 and 18:00.';
}
