<?php

declare(strict_types=1);

namespace Drupal\block_play\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides an editor menu links block.
 *
 * @Block(
 *   id = "editor_menu_links_block",
 *   admin_label = @Translation("Editor Menu Links"),
 *   category = @Translation("DOI"),
 * )
 */
final class EditorMenuLinksBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MessengerInterface $messenger,
    AccountInterface $currentUser,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
    $this->currentUser = $currentUser;
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    // get the user's roles
//    $currentUser = \Drupal::currentUser();
//    $roles = $currentUser->getRoles();
    $roles = $this->currentUser->getRoles();
    $roles_string = implode(', ', $roles);
    $build['content'] = [
      '#markup' => $this->t('It works! - User roles: @roles', ['@roles' => $roles_string]),
    ];
    //$build['drupalist_activate_block']['#markup'] = '<p>Your user id is ' . $uid = $this->currentUser->id() . '</p>';
    return $build;
  }

}
