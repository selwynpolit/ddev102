<?php

declare(strict_types=1);

namespace Drupal\serviceplay;

use Drupal\Core\Pager\PagerManagerInterface;

/**
 * @todo Add class description.
 */
final class Example implements ExampleInterface {

  /**
   * Constructs an Example object.
   */
  public function __construct(
    private readonly PagerManagerInterface $pagerManager,
  ) {}

  /**
   * {@inheritdoc}
   */
  public function getSomething(): string {
    return 'something else also';
  }

}
