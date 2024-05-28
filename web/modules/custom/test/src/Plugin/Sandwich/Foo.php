<?php

declare(strict_types=1);

namespace Drupal\test\Plugin\Sandwich;

use Drupal\test\SandwichPluginBase;

/**
 * Plugin implementation of the sandwich.
 *
 * @Sandwich(
 *   id = "foo",
 *   label = @Translation("Foo"),
 *   description = @Translation("Foo description.")
 * )
 */
final class Foo extends SandwichPluginBase {

}
