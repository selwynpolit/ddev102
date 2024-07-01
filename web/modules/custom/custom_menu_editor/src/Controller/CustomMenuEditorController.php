<?php

namespace Drupal\custom_menu_editor\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

class CustomMenuEditorController extends ControllerBase {

  public function menuList() {
    $menus = \Drupal::entityTypeManager()->getStorage('menu')->loadMultiple();

    $rows = [];
    foreach ($menus as $menu) {
      $edit_url = Url::fromRoute('custom_menu_editor.edit_menu', ['menu' => $menu->id()]);
      $rows[] = [
        $menu->label(),
        $menu->id(),
        [
          'data' => [
            '#type' => 'link',
            '#title' => $this->t('Edit'),
            '#url' => $edit_url,
          ],
        ],
      ];
    }

    $build['table'] = [
      '#type' => 'table',
      '#header' => [$this->t('Title'), $this->t('Machine name'), $this->t('Operations')],
      '#rows' => $rows,
    ];

    return $build;
  }
}
