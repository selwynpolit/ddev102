<?php

declare(strict_types=1);

namespace Drupal\doi_workbench;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Menu Listing Service.
 */
final class MenuListing {

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
  protected $currentUser;


  /**
   * Pager manager service.
   *
   * @var \Drupal\Core\Pager\PagerManagerInterface
   */
  protected PagerManagerInterface $pagerManager;

  protected RouteMatchInterface $routeMatch;

  /**
   * The controller constructor.
   */
  public function __construct(
    MessengerInterface $messenger,
    RouteMatchInterface $routeMatch,
    AccountProxyInterface $currentUser,
    PagerManagerInterface $pagerManager,
  ) {
    $this->messenger = $messenger;
    $this->routeMatch = $routeMatch;
    $this->currentUser = $currentUser;
    $this->pagerManager = $pagerManager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('messenger'),
      $container->get('current_route_match'),
      $container->get('current_user'),
      $container->get('pager.manager'),
    );
  }

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

  /**
   *
   */
  public function build() {
    // Total number of items you have.
    $totalItems = 50;
    // How many items per page you want.
    $itemsPerPage = 10;
    $currentPage = $this->pagerManager->createPager($totalItems, $itemsPerPage)->getCurrentPage();

    // Generate the content for the current page.
    $build['content'] = $this->generateSinglePage($currentPage);

    // Add the pager to the build.
    $build['pager'] = [
      '#type' => 'pager',
    ];

    // Attach the library
    $build['#attached']['library'][] = 'doi_workbench/ajax_pager';
    return $build;
  }

  /**
   *
   */
  public function getUpdatedContent() {
    $response = new AjaxResponse();
    $command = new ReplaceCommand('.content', $this->generateSinglePage(1));
    $response->addCommand($command);
    return $response;

//    $currentPage = $this->pagerManager->createPager($totalItems, $itemsPerPage)->getCurrentPage();
//    return $this->generateSinglePage();

  }

  /**
   *
   */
  protected function generateSinglePage(int $currentPage = 0): array {
    // Generate 5 pages worth of items.
    $items = [];
    for ($i = 0; $i < 50; $i++) {
      $items[] = 'Item ' . $i;
    }
    $currentItems = array_slice($items, $currentPage * 10, 10);
    // Generate a table with the items.
    $build = [
      '#type' => 'table',
      //'#header' => [$this->t('Item')],
      '#header' => [\Drupal::translation()->translate('Item')],
      '#attributes' => ['class' => ['my-menu-list']],
      '#rows' => array_map(function ($item) {
        return [$item];
      }, $currentItems),
    ];

    return $build;
  }

}
