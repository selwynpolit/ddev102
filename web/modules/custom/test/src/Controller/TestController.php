<?php

declare(strict_types=1);

namespace Drupal\test\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Test routes.
 */
final class TestController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

}
