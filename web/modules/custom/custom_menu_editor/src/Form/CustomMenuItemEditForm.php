<?php

namespace Drupal\custom_menu_editor\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\Core\Url;

class CustomMenuItemEditForm extends FormBase {

  public function getFormId() {
    return 'custom_menu_item_edit_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state, $menu = NULL, MenuLinkContent $menu_link_content = NULL) {
    \Drupal::logger('custom_menu_editor')->notice('Attempting to build menu item edit form for menu: @menu, item: @item', ['@menu' => $menu->id(), '@item' => $menu_link_content->uuid()]);
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Menu link title'),
      '#default_value' => $menu_link_content->getTitle(),
      '#required' => TRUE,
      '#description' => $this->t('The text to be used for this link in the menu.'),
    ];

    $url = $menu_link_content->getUrlObject();
    if (!$url->isExternal()) {
      $link_path = $url->getInternalPath();
    }
    else {
      $link_path = $url->getUri();
    }

    $form['link'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Link'),
      '#default_value' => $link_path,
      '#required' => TRUE,
      '#description' => $this->t('The path for this menu link. This can be an internal Drupal path such as /node/add or an external URL such as http://example.com.'),
    ];

    $form['description'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Description'),
      '#default_value' => $menu_link_content->getDescription(),
      '#description' => $this->t('Shown when hovering over the menu link.'),
    ];

    $form['enabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enabled'),
      '#default_value' => $menu_link_content->isEnabled(),
      '#description' => $this->t('Menu links that are not enabled will not be shown in any menu.'),
    ];

    $form['expanded'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show as expanded'),
      '#default_value' => $menu_link_content->isExpanded(),
      '#description' => $this->t('If selected and this menu link has children, the menu will always appear expanded.'),
    ];

    $form['weight'] = [
      '#type' => 'weight',
      '#title' => $this->t('Weight'),
      '#default_value' => $menu_link_content->getWeight(),
      '#description' => $this->t('Optional. In the menu, the heavier items will sink and the lighter items will be positioned nearer the top.'),
      '#delta' => 50,
    ];

    $form['parent'] = [
      '#type' => 'select',
      '#title' => $this->t('Parent link'),
      '#default_value' => $menu_link_content->getParentId(),
      '#options' => $this->getParentOptions($menu, $menu_link_content->id()),
      '#description' => $this->t('The parent menu link of this menu link. The maximum depth for a link and all its children is fixed. Some menu links may not be available as parents if selecting them would exceed this limit.'),
    ];

    $form['menu_link_id'] = [
      '#type' => 'hidden',
      '#value' => $menu_link_content->id(),
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save'),
    ];

    return $form;
  }

  public function submitForm(array &$form, FormStateInterface $form_state) {
    $menu_link_id = $form_state->getValue('menu_link_id');
    $menu_link = MenuLinkContent::load($menu_link_id);

    $menu_link->set('title', $form_state->getValue('title'));
    $menu_link->set('link', ['uri' => $form_state->getValue('link')]);
    $menu_link->set('description', $form_state->getValue('description'));
    $menu_link->set('enabled', $form_state->getValue('enabled'));
    $menu_link->set('expanded', $form_state->getValue('expanded'));
    $menu_link->set('weight', $form_state->getValue('weight'));
    $menu_link->set('parent', $form_state->getValue('parent'));

    $menu_link->save();

    $this->messenger()->addMessage($this->t('The menu link %title has been updated.', ['%title' => $form_state->getValue('title')]));
    $form_state->setRedirect('custom_menu_editor.edit_menu', ['menu' => $menu_link->getMenuName()]);
  }

  protected function getParentOptions($menu_name, $exclude = NULL) {
    $options = [];
    $options[''] = '<' . $this->t('root') . '>';

    $menu_tree = \Drupal::menuTree();
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);

    $tree = $menu_tree->load($menu_name, $parameters);
    foreach ($tree as $element) {
      if ($exclude && $element->link->getPluginId() == $exclude) {
        continue;
      }
      $options[$element->link->getPluginId()] = str_repeat('-', $element->depth) . ' ' . $element->link->getTitle();
    }

    return $options;
  }
}
