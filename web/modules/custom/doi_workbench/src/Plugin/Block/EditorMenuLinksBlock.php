<?php

declare(strict_types=1);

namespace Drupal\doi_workbench\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\doi_workbench\MenuListing;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an editor menu links block.
 *
 * @Block(
 *   id = "doi_workbench_editor_menu_links",
 *   admin_label = @Translation("Paged Editor Menu Links"),
 *   category = @Translation("Custom"),
 * )
 */
final class EditorMenuLinksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Account service.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected AccountInterface $currentUser;

  /**
   * Pager manager service.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected PagerManagerInterface $pagerManager;

  /**
   * Menu listing service.
   *
   * @var \Drupal\doi_workbench\MenuListing
   */
  protected MenuListing $menuListing;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MessengerInterface $messenger,
    AccountProxyInterface $currentUser,
    PagerManagerInterface $pagerManager,
    MenuListing $menuListing,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
    $this->currentUser = $currentUser;
    $this->pagerManager = $pagerManager;
    $this->menuListing = $menuListing;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('messenger'),
      $container->get('current_user'),
      $container->get('pager.manager'),
      $container->get('doi_workbench.menu_listing'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build = $this->menuListing->build();
    return $build;
  }

}
