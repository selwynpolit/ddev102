<?php

namespace Drupal\menu_permissions\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\menu_link_content\MenuLinkContentAccessControlHandler;

/**
 * Defines the access control handler for the menu link content entity type.
 */
//class MenuLinkContentAccessHandler extends EntityAccessControlHandler {
class MenuLinkContentAccessHandler extends MenuLinkContentAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    // Custom access logic for MenuLinkContent entities.

    $type = $entity->getEntityTypeId();
    if ($type == 'menu_link_content') {
      $x = $entity->get('menu_name')->value;
    }

    switch ($operation) {
      case 'view':
        // Allow view access based on custom logic or permissions.
        return AccessResult::allowedIfHasPermission($account, 'edit menu links');
//        return AccessResult::allowed();

      case 'update':
//        if ($type == 'menu_link_content') {
//          return AccessResult::forbidden();
//        }
        // Allow update access based on custom logic or permissions.
        return AccessResult::allowedIfHasPermission($account, 'edit menu links');
//        return AccessResult::allowed();

      case 'delete':
        // This shows/removes the delete button the edit page.
//        return AccessResult::allowedIfHasPermission($account, 'delete menu link content');
        return AccessResult::allowedIfHasPermission($account, 'edit menu links');
    }

    // No opinion on other operations.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    // Allow create access based on custom logic or permissions.
    return AccessResult::allowedIfHasPermission($account, 'add menu link content');
  }

}
