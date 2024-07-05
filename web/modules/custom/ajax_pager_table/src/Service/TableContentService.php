<?php
// In your module's src/Service folder, create a new file named TableContentService.php

namespace Drupal\ajax_pager_table\Service;

use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Pager\PagerManagerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Core\Url;

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
  public function getTableContent($page): array {
    $items_per_page = 10;
    $total_items = 50;
    $start = $page * $items_per_page;
    $end = min($start + $items_per_page, $total_items);
    $total_pages = ceil($total_items / $items_per_page);


    $pager = $this->pagerManager->createPager($total_items, $items_per_page, 0);
    //$current_page = $pager->getCurrentPage();
    // Slice the array of menus for the current page.
    //$pagedMenus = array_slice($accessibleMenus, $currentPage * $itemsPerPage, $itemsPerPage, TRUE);

    $header = [
      ['data' => t('Item Number')],
    ];

    $rows = [];
    for ($i = $start; $i < $end; $i++) {
      $rows[] = ['data' => ['Item ' . $i]];
    }

    $table = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $rows,
    ];

    //Add pagination if necessary.
    if ($total_pages > 1) {
      $pager_array = [
        '#type' => 'pager',
        '#element' => 0,
      ];
    }

    //$pager = $this->getPager($page, $items_per_page, $total_items);

    return [
      'table' => $table,
      'pager' => $pager_array,
    ];
  }

//  /**
//   * Generates the pager links.
//   *
//   * @param int $current_page
//   *   The current page number.
//   * @param int $items_per_page
//   *   Number of items per page.
//   * @param int $total_items
//   *   Total number of items.
//   *
//   * @return array
//   *   The render array of the pager.
//   */
//  public function getPager($current_page, $items_per_page, $total_items) {
//    $total_pages = ceil($total_items / $items_per_page);
//    $pager = [];
//
//    for ($i = 0; $i < $total_pages; $i++) {
//      $pager[] = [
//        '#type' => 'link',
//        '#title' => $i + 1 . ' ',
////        '#url' => Url::fromRoute('ajax_pager_table.load_page', ['page' => $i]),
//        '#url' => Url::fromRoute('ajax_pager.retrieve_page', ['page' => $i]),
//        '#attributes' => [
//          'class' => ['pager-link'],
//          'data-page' => $i,
//        ],
//      ];
//    }
//
//    return [
//      '#type' => 'container',
//      '#attributes' => ['class' => ['pager']],
//      'links' => $pager,
//    ];
//  }

}
