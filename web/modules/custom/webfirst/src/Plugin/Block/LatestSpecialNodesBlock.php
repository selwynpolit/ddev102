<?php

declare(strict_types=1);

namespace Drupal\webfirst\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Datetime\DateFormatterInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a latest special nodes block.
 *
 * @Block(
 *   id = "webfirst_latest_special_nodes",
 *   admin_label = @Translation("Latest special nodes"),
 *   category = @Translation("Selwyn"),
 * )
 */
final class LatestSpecialNodesBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Constructs the plugin instance.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly DateFormatterInterface $dateFormatter,
    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): self {
    return new self(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('date.formatter'),
      $container->get('entity_type.manager'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $abundant_numbers = [12, 18, 20, 24, 30];
    $build = [];


    // Get the list of nodes by date order.
    $query = $this->entityTypeManager->getStorage('node')->getQuery();
    $query->condition('status', 1)
      ->condition('type', 'page')
      ->accessCheck(FALSE)
      ->sort('created', 'DESC');
    $nids = $query->execute();

    $items = [];
    // Extract the special nodes and add them to a ul.
    $nodes = $this->entityTypeManager->getStorage('node')->loadMultiple($nids);
    foreach ($nodes as $node) {
      $created = $node->getCreatedTime();
      $day = $this->dateFormatter->format($created, 'custom', 'd');
      $created = $this->dateFormatter->format($created, 'custom', 'm/d/Y');
      if (in_array($day, $abundant_numbers)) {
        $items[] = $node->getTitle() . ' (' . $created . ')';
      }
    }

    $build['content'] = [
      '#theme' => 'item_list',
      '#items' => $items,

    ];
//    $build['#attributes'] = [
//      'class' => ['special-nodes'],
//    ];

    $build['#attached']['library'][] = 'webfirst/special_nodes_block';
    return $build;
  }

}
