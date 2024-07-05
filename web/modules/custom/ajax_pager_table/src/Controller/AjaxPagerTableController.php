<?php

namespace Drupal\ajax_pager_table\Controller;

use Drupal\ajax_pager_table\Service\TableContentService;
use Drupal\Core\Ajax\AddCssCommand;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\AppendCommand;
use Drupal\Core\Ajax\BeforeCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Ajax\RemoveCommand;
use Drupal\Core\Ajax\ReplaceCommand;
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

  public function refreshAjaxBlock(Request $request) {
    if (!$request->isXmlHttpRequest()) {
      throw new HttpException(400, 'This is not an AJAX request.');
    }
    //$page = $request->query->get('page');
    $uri = $request->getUri();
    $path = parse_url($uri, PHP_URL_PATH);
    $segments = explode('/', trim($path, '/'));
    $page_number = (int) end($segments);
    $response = new AjaxResponse();
    $command = new RemoveCommand('#ajax-pager-table-wrapper');
    $response->addCommand($command);
    $command = new BeforeCommand('#ajax-pager-wrapper', $this->tableContentService->getTableContent($page_number));
    $response->addCommand($command);


    // Remove 'pager_item--active' class from all pager items.
    $removeActiveClassSelector = 'li.pager__item';
    $removeActiveClassMethod = 'removeClass';
    $removeActiveClassArgs = ['pager__item--active'];
    $command = new InvokeCommand($removeActiveClassSelector, $removeActiveClassMethod, $removeActiveClassArgs);
    $response->addCommand($command);

    // Add 'pager_item--active' class to the clicked item.
    // Assuming the links are correctly structured to include the page number in their href attribute.
    $addActiveClassSelector = 'li.pager__item a[href*="/refresh-selwyn-wrapper/' . $page_number . '"]';
    $addActiveClassMethod = 'addClass';
    $addActiveClassArgs = ['pager__item--active'];
    $command = new InvokeCommand($addActiveClassSelector, $addActiveClassMethod, $addActiveClassArgs);
    $response->addCommand($command);


    return $response;
  }

}
