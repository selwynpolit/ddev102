<?php

declare(strict_types=1);

namespace Drupal\block_play\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a test block1 block.
 *
 * @Block(
 *   id = "block_play_test_block1",
 *   admin_label = @Translation("test block1"),
 *   category = @Translation("Custom"),
 * )
 */
final class TestBlock1Block extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works and then some!'),
    ];

    return $build;
  }

}
