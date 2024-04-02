<?php

namespace Drupal\lb_plus\Event;

use Drupal\Component\EventDispatcher\Event;
use Drupal\Core\Block\BlockPluginInterface;

class PlaceBlockEvent extends Event {
  private BlockPluginInterface $blockPlugin;
  private string $blockPluginId;
  private ?string $bundle;

  public function __construct(BlockPluginInterface $block_plugin) {
    $this->blockPlugin = $block_plugin;
    $block_plugin_id = $block_plugin->getPluginId();
    if (str_contains($block_plugin_id, ':')) {
      [$block_plugin_id, $bundle] = explode(':', $block_plugin_id);
    } else {
      $bundle = NULL;
    }

    $this->blockPluginId = $block_plugin_id;
    $this->bundle = $bundle;
  }

  /**
   * @return mixed
   */
  public function getBlockPlugin() {
    return $this->blockPlugin;
  }

  public function getBlockPluginId(): string {
    return $this->blockPluginId;
  }

  public function getBundle(): ?string {
    return $this->bundle;
  }

}
