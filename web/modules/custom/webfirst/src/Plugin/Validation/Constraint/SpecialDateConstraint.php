<?php

declare(strict_types=1);

namespace Drupal\webfirst\Plugin\Validation\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * Provides a Special Date constraint.
 *
 * @Constraint(
 *   id = "SpecialDate",
 *   label = @Translation("Special Date", context = "Validation"),
 * )
 */
final class SpecialDateConstraint extends Constraint {

  public array $abundantNumbers = [12, 18, 20, 24, 30];

  public string $message = 'The value must be one of the following: 12, 18, 20, 24, 30';
}
