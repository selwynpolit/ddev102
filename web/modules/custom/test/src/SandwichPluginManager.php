<?php

declare(strict_types=1);

namespace Drupal\test;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\test\Annotation\Sandwich;

/**
 * Sandwich plugin manager.
 */
final class SandwichPluginManager extends DefaultPluginManager {

  /**
   * Constructs the object.
   */
  public function __construct(\Traversable $namespaces, CacheBackendInterface $cache_backend, ModuleHandlerInterface $module_handler) {
    parent::__construct('Plugin/Sandwich', $namespaces, $module_handler, SandwichInterface::class, Sandwich::class);
    $this->alterInfo('sandwich_info');
    $this->setCacheBackend($cache_backend, 'sandwich_plugins');
  }

}
