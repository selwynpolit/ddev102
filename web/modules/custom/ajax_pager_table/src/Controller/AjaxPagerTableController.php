<?php

namespace Drupal\ajax_pager_table\Controller;

use Drupal\ajax_pager_table\Service\TableContentService;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\MessengerInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 *
 */
class AjaxPagerTableController extends ControllerBase {

  /**
   * The table content service.
   *
   * @var \Drupal\ajax_pager_table\Service\TableContentService
   */
  protected $tableContentService;

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  public function __construct(
    MessengerInterface $messenger,
    TableContentService $tableContentService,
    AccountInterface $current_user,
  ) {
    $this->tableContentService = $tableContentService;
    $this->messenger = $messenger;
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('messenger'),
      $container->get('ajax_pager_table.table_content_service'),
      $container->get('current_user'),
    );
  }

  public function getAjaxPage(Request $request) {
    if (!$request->isXmlHttpRequest()) {
      throw new HttpException(400, 'This is not an AJAX request.');
    }
    $page = $request->query->get('page');
    $table = $this->tableContentService->getTableContent($page);
    return new JsonResponse($table);

  }

}
