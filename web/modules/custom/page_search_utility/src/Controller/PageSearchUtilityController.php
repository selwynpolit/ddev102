<?php

declare(strict_types=1);

namespace Drupal\page_search_utility\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;

/**
 * Returns responses for Page search utility routes.
 */
final class PageSearchUtilityController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function __invoke(): array {
    $string_to_find = 'abc.example.com';
    $connection = \Drupal::database();
    $build = [];
    $this->find_string_in_nodes($string_to_find, $connection, $build);

    $build['content'][] = [
      '#type' => 'item',
      '#markup' => $this->t('Finished'),
    ];

    return $build;
  }

  function find_string_in_nodes($string, Connection $connection, array &$build): void {
    // Create entity query to load all published nodes.
    $query = \Drupal::entityQuery('node');
    $query->condition('status', 1);
    $query->accessCheck(FALSE);
    $nids = $query->execute();
    $count = count($nids);


//    $query = $connection->select('node', 'n');
//    $query->fields('n', ['nid']);
//    //$query->condition('n.type', 'page');
//    $results = $query->execute();

    // Show message indicating number of nodes to be processed.
    $build['content'][] = [
      '#title' => 'Processing nodes',
      '#type' => 'item',
      '#markup' => 'Processing ' . $count . ' nodes',
    ];

    foreach ($nids as $nid) {
      $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);
      // If node doesn't load, report the nid and continue.
      if (!$node) {
        $build['content'][] = [
          '#title' => 'Node not found',
          '#type' => 'item',
          '#markup' => 'Node not found: ' . $nid,
        ];
        continue;
      }
      $nid = $node->id();
      $type = $node->bundle();
      $url = $node->toUrl();

      $renderer = \Drupal::service('renderer');
      $view_builder = \Drupal::entityTypeManager()->getViewBuilder('node');
      $render_array = $view_builder->view($node, 'full');
//      $rendered_node = $renderer->renderPlain($render_array);
      $rendered_node = $renderer->render($render_array);
      //$rendered_node = $renderer->renderInIsolation($render_array);

      if (str_contains($rendered_node->__toString(), $string)) {
        // print 'Found links to "' . $string . '" in node ' . $nid . PHP_EOL;
        //$result = 'Found links to "' . $string . '" in node ' . $nid . PHP_EOL;
        $result = 'Found link(s) to "' . $string . '" in node ' . $nid . ' of type ' . $type . PHP_EOL;
        $build['content'][] = [
          '#prefix' => '<div>',
          '#title' => $result,
          '#type' => 'link',
          '#url' => $url,
          '#suffix' => '</div>',
        ];
      }
    }
  }

}
