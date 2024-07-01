<?php

declare(strict_types=1);

namespace Drupal\menu_permissions\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityFormBuilderInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Menu\MenuLinkManagerInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Returns responses for Menu permissions routes.
 */
final class MenuEditController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;


//  /**
//   * The menu link manager service.
//   *
//   * @var \Drupal\Core\Menu\MenuLinkManagerInterface
//   */
//  protected MenuLinkManagerInterface $menuLinkManager;

  /**
   * The controller constructor.
   */
  public function __construct(
//    private readonly AccountProxyInterface $currentUser,
    AccountProxyInterface $current_user,
    private readonly RouteMatchInterface $routeMatch,
    private readonly CurrentPathStack $pathCurrent,
    private readonly RequestStack $requestStack,
//    private readonly MenuLinkManagerInterface $menuLinkManager,
//    private readonly EntityFormBuilderInterface $entityFormBuilder,
//    private readonly EntityTypeManagerInterface $entityTypeManager
  ) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('current_user'),
      $container->get('current_route_match'),
      $container->get('path.current'),
      $container->get('request_stack'),
//      $container->get('menu.link_manager'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

//    if (!$this->currentUser->hasPermission('edit menu links')) {
//      throw new AccessDeniedHttpException();
//    }

    $menu = $this->entityTypeManager()->getStorage('menu')->load('menu-1');

    $form = $this->entityFormBuilder()->getForm($menu, 'edit');
    // Fix this form so that it redirects back to itself after the submit





    return $form;

  }

}
