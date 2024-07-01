<?php

namespace Drupal\custom_menu_editor\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Session\AccountInterface;
use Drupal\system\Entity\Menu;
use Drupal\menu_link_content\Entity\MenuLinkContent;
use Drupal\Core\Routing\Access\AccessInterface;

class CustomMenuAccessCheck implements AccessInterface {

  public function checkMenuAccess(AccountInterface $account, Menu $menu = NULL) {
    // If no specific menu is provided, just check the general permission
    if ($menu === NULL) {
      return AccessResult::allowedIfHasPermission($account, 'edit menus and menu items');
    }

    // Check permission and if the menu exists
    return AccessResult::allowedIf($account->hasPermission('edit menus and menu items') && $menu !== NULL);
  }

  public function checkMenuItemAccess(AccountInterface $account, Menu $menu = NULL, MenuLinkContent $menu_link_content = NULL) {
    // Check basic permission first
    if (!$account->hasPermission('edit menus and menu items')) {
      return AccessResult::forbidden();
    }

    // If either menu or menu_link_content is null, deny access
    if ($menu === NULL || $menu_link_content === NULL) {
      return AccessResult::forbidden();
    }

    // Check if the menu item belongs to the specified menu
    if ($menu_link_content->getMenuName() !== $menu->id()) {
      return AccessResult::forbidden();
    }

    return AccessResult::allowed();
  }
}
