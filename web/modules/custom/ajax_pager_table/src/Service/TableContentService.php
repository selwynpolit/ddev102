<?php

// In your module's src/Service folder, create a new file named TableContentService.php.
namespace Drupal\ajax_pager_table\Service;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;

/**
 * Service for fetching table content.
 */
class TableContentService {

  public function __construct(
    private readonly MessengerInterface $messenger,
    private readonly AccountProxyInterface $currentUser,
    private readonly PagerManagerInterface $pagerManager,
  ) {}

  /**
   * Generates the table content.
   *
   * @param int $page
   *   The current page number.
   *
   * @return array
   *   The render array of the table.
   */
  public function getTableContent(int $page, bool $retrieve_pager = FALSE): array {
    $items_per_page = 10;
    $total_items = 105;

    // $start = $page * $items_per_page;
    //    $end = min($start + $items_per_page, $total_items);
    //    $total_pages = ceil($total_items / $items_per_page);
    $pager = $this->pagerManager->getPager(0);
    if (is_null($pager)) {
      $pager = $this->pagerManager->createPager($total_items, $items_per_page, 0);
    }
    $current_page = $pager->getCurrentPage();
    $current_page = $page;
    $start = $current_page * $items_per_page;
    $end = min($start + $items_per_page, $total_items);

    $header = [
      ['data' => t('Item Number')],
    ];

    $rows = [];
    for ($i = $start; $i < $end; $i++) {
      $rows[] = ['data' => ['Item ' . $i]];
    }

    $build['table_content']['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
      '#prefix' => '<div id="ajax-pager-table-wrapper">',
      '#suffix' => '</div>',
    ];
    if ($retrieve_pager) {
      if ($total_items > 1) {
        $build['table_content']['pager'] = [
          '#type' => 'pager',
          '#element' => 0,
          '#prefix' => '<div id="ajax-pager-wrapper">',
          '#suffix' => '</div>',
        ];
      }
    }

    return $build;
  }

}
