<?php

declare(strict_types=1);

namespace Drupal\webfirst\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Validates the Special Date constraint.
 */
final class SpecialDateConstraintValidator extends ConstraintValidator {

  /**
   * {@inheritdoc}
   */
  public function validate(mixed $value, Constraint $constraint): void {
    //$abundant_numbers = [12, 18, 20, 24, 30];
    $date_formatter = \Drupal::service('date.formatter');
    $day = $date_formatter->format($value, 'custom', 'd');
    if (!in_array($day, $constraint->abundant_numbers)) {
      //$this->context->addViolation('The value must be one of the following: 12, 18, 20, 24, 30');
      $this->context->addViolation($constraint->message);
    }
  }

}
