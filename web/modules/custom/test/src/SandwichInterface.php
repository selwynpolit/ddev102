<?php

declare(strict_types=1);

namespace Drupal\test;

/**
 * Interface for sandwich plugins.
 */
interface SandwichInterface {

  /**
   * Returns the translated plugin label.
   */
  public function label(): string;

}
