<?php

declare(strict_types=1);

namespace Drupal\route_play\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Link;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\PathValidatorInterface;
use Drupal\Core\Url;
use Drupal\route_play\HelloWorldSalutation;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Route play routes.
 */
final class RoutePlayController extends ControllerBase {

  /**
   * The path validator.
   *
   * @var \Drupal\Core\Path\PathValidatorInterface
   */
  private PathValidatorInterface $pathValidator;
  /**
   * The current path.
   *
   * @var \Drupal\Core\Path\CurrentPathStack
   */
  private CurrentPathStack $pathCurrent;
  /* @var \Drupal\Core\Entity\EntityTypeManagerInterface */
  protected $entityTypeManager;

  /**
   * Hello world salutation service.
   *
   * @var \Drupal\route_play\HelloWorldSalutation
   */
  protected $helloWorldSalutationService;

  /**
   * The controller constructor.
   */
  public function __construct(
    PathValidatorInterface $pathValidator,
    CurrentPathStack $pathCurrent,
    EntityTypeManagerInterface $entityTypeManager,
    HelloWorldSalutation $helloWorldSalutationService,
//    private readonly PathValidatorInterface $pathValidator,
//    private readonly CurrentPathStack $pathCurrent,
//    private readonly EntityTypeManagerInterface $entityTypeManager,
  ) {
    $this->pathValidator = $pathValidator;
    $this->pathCurrent = $pathCurrent;
    $this->entityTypeManager = $entityTypeManager;
    $this->helloWorldSalutationService = $helloWorldSalutationService;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('path.validator'),
      $container->get('path.current'),
      $container->get('entity_type.manager'),
      $container->get('route_play.hello_world_salutation'),
    );
  }

  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function salutation(): array {
    $node_storage = $this->entityTypeManager->getStorage('node');
    // Build a query to load all recipe nodes.
    $query = $node_storage->getQuery()
      ->condition('type', 'recipe')
      ->condition('status', 1)
      ->sort('created', 'DESC')
      ->accessCheck(FALSE);

    // Get the NIDs of the nodes.
    $nids = $query->execute();
    // Load the nodes.
    $nodes = $node_storage->loadMultiple($nids);
    $items = [];
    foreach ($nodes as $node) {
      // Get the alias for the node
      $alias = \Drupal::service('path_alias.manager')->getAliasByPath('/node/' . $node->id());
      $items[] = $node->getTitle() . ' (' . $node->id() . ') - ' . $alias;
    }
    // Unordered list
    $build['content'] = [
      '#theme' => 'item_list',
      '#list_type' => 'ul',
      '#items' => $items,
      '#attributes' => [
        'class' => 'recipe-list',
      ],
    ];

    // Static version.
//    $hello_world_service = \Drupal::service('route_play.hello_world_salutation');
//    $hello = $hello_world_service->getSalutation();
    // DI version.
    $hello = $this->helloWorldSalutationService->getSalutation();


    $url = Url::fromUri('internal:/node/9');
    $link = \Drupal::service('link_generator')->generate('Scottish Pie', $url);
    // Render the link.
//    $build['link'] = [
//      '#markup' => $link,
//    ];


    $build['scottish_pie'] = [
      '#type' => 'link',
      '#title' => $this->t('Scottish Pie'),
      '#url' => Url::fromUri('internal:/node/9'),
      '#attributes' => [
        'class' => ['scottish-pie-class'],
      ],
    ];

    $nid = 9;
    $url = Url::fromRoute('entity.node.canonical', ['node' => $nid]);
    //$url = Url::fromUri('internal:/node/9');
    $link_text =  [
      '#type' => 'html_tag',
      '#tag' => 'div',
      '#attributes' => [
        'class' => ['load-more-class'],
      ],
      '#value' => $this->t('Load More'),
    ];
    $link = Link::fromTextAndUrl($link_text, $url);
    $build['load_more'] = [
      '#markup' => $link->toString(),
    ];



    $build['hello_world'] = [
      '#markup' => $hello,
    ];
    //$build['#cache']['max-age'] = 0;
    return $build;
  }

}
