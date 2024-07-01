<?php

namespace Drupal\custom_menu_editor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\system\Entity\Menu;
use Drupal\Core\Url;

class CustomMenuEditForm extends FormBase {

  public function getFormId() {
    return 'custom_menu_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, Menu $menu = NULL) {
    \Drupal::logger('custom_menu_editor')->notice('Building menu edit form for menu: @menu', ['@menu' => $menu->id()]);
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Menu name'),
      '#default_value' => $menu->label(),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $menu->id(),
      '#machine_name' => [
        'exists' => '\Drupal\system\Entity\Menu::load',
      ],
      '#disabled' => TRUE,
    ];

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $menu->getDescription(),
    ];

    // Add a list of menu items with edit links
    $menu_tree = \Drupal::menuTree();
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu->id());
    $tree = $menu_tree->load($menu->id(), $parameters);

    $form['menu_items'] = [
      '#type' => 'table',
      '#header' => [$this->t('Menu item'), $this->t('Operations')],
      '#empty' => $this->t('No menu items available.'),
    ];

    foreach ($tree as $element) {
      $menu_link = $element->link;
      $menu_link_id = $menu_link->getPluginId();
      if (strpos($menu_link_id, 'menu_link_content:') === 0) {
        $uuid = substr($menu_link_id, strlen('menu_link_content:'));
        $url = Url::fromRoute('custom_menu_editor.edit_menu_item', [
          'menu' => $menu->id(),
          'menu_link_content' => $uuid,
        ]);
        \Drupal::logger('custom_menu_editor')->notice('Generating edit link for menu item: @uuid in menu: @menu', ['@uuid' => $uuid, '@menu' => $menu->id()]);
        $form['menu_items'][$menu_link_id] = [
          'title' => ['#markup' => $menu_link->getTitle()],
          'operations' => [
            'data' => [
              '#type' => 'link',
              '#title' => $this->t('Edit'),
              '#url' => $url,
            ],
          ],
        ];
      }
    }

//    foreach ($tree as $element) {
//      $menu_link = $element->link;
//      $menu_link_id = $menu_link->getPluginId();
//      if (strpos($menu_link_id, 'menu_link_content:') === 0) {
//        $menu_link_content = MenuLinkContent::load(substr($menu_link_id, strlen('menu_link_content:')));
//        if ($menu_link_content) {
//          $url = Url::fromRoute('custom_menu_editor.edit_menu_item', [
//            'menu' => $menu->id(),
//            'menu_link_content' => $menu_link_content->id(),
//          ]);
//
//          $form['menu_items'][$menu_link_id] = [
//            'title' => ['#markup' => $menu_link->getTitle()],
//            'operations' => [
//              'data' => [
//                '#type' => 'link',
//                '#title' => $this->t('Edit'),
//                '#url' => $url,
//              ],
//            ],
//          ];
//        }
//      }
//    }

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $menu = Menu::load($form_state->getValue('id'));
    $menu->set('label', $form_state->getValue('label'));
    $menu->set('description', $form_state->getValue('description'));
    $menu->save();

    $this->messenger()->addMessage($this->t('The menu %menu has been updated.', ['%menu' => $form_state->getValue('label')]));
    $form_state->setRedirect('custom_menu_editor.menu_list');
  }
}
