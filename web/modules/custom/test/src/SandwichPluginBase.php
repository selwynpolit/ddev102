<?php

declare(strict_types=1);

namespace Drupal\test;

use Drupal\Component\Plugin\PluginBase;

/**
 * Base class for sandwich plugins.
 */
abstract class SandwichPluginBase extends PluginBase implements SandwichInterface {

  /**
   * {@inheritdoc}
   */
  public function label(): string {
    // Cast the label to a string since it is a TranslatableMarkup object.
    return (string) $this->pluginDefinition['label'];
  }

}
