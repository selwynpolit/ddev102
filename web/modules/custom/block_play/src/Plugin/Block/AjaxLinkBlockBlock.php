<?php

declare(strict_types=1);

namespace Drupal\block_play\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Url;

/**
 * Provides an ajax link block block.
 *
 * @Block(
 *   id = "block_play_ajax_link_block",
 *   admin_label = @Translation("Ajax Link Block"),
 *   category = @Translation("Custom"),
 * )
 */
final class AjaxLinkBlockBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build[] = [
      '#theme' => 'container',
      '#children' => [
        '#markup' => $this->t('Using container - Here is the block showing up - click to make it do something!'),
      ],
    ];

    $build['content'] = [
      '#markup' => $this->t('Here is the block showing up - click to make it do something!'),
      '#prefix' => '<div class="my-menu-list">',
      '#suffix' => '</div>',
    ];

    $url = Url::fromRoute('block_play.hide_block');
    $url->setOption('attributes', ['class' => ['use-ajax']]);
    $build['link'] = [
      '#type' => 'link',
      '#url' => $url,
      '#title' => 'Click my special link to make something happen',
    ];

    return $build;
  }

}
