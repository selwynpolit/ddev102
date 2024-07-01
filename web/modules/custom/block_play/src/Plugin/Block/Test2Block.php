<?php

declare(strict_types=1);

namespace Drupal\block_play\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a test2 block.
 *
 * @Block(
 *   id = "block_play_test2",
 *   admin_label = @Translation("test2"),
 *   category = @Translation("DOI"),
 * )
 */
final class Test2Block extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    MessengerInterface $messenger,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->messenger = $messenger;
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
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $build['content'] = [
      '#markup' => $this->t('It works!'),
    ];
    $currentUser = \Drupal::currentUser();
    $roles = $currentUser->getRoles();
    $roles_string = implode(', ', $roles);
    $build['content'] = [
      '#markup' => $this->t('It works! - User roles: @roles', ['@roles' => $roles_string]),
    ];


    return $build;
  }

}
