<?php

declare(strict_types=1);

namespace Drupal\block_play\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ReplaceCommand;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Returns responses for Block Play routes.
 */
final class BlockPlayController extends ControllerBase {

  /**
   * The current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $currentUser;

  public function __construct(
    AccountInterface $current_user,
  ) {
    $this->currentUser = $current_user;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('current_user'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
    ];

    return $build;
  }

  /**
   *
   */
  public function changeBlock(Request $request) {
    if (!$request->isXmlHttpRequest()) {
      throw new HttpException(400, 'This is not an AJAX request.');
    }
    $user_name = $this->currentUser->getDisplayName();
    $response = new AjaxResponse();
    // $command = new RemoveCommand('#block-olivero-ajaxlinkblock');
    // $command = new AppendCommand('#block-olivero-ajaxlinkblock', 'Block hidden - or is it?');
    // $response->addCommand(new AppendCommand('#ajax-example-destination-div', 'Block hidden'));
    $command = new ReplaceCommand('#block-olivero-ajaxlinkblock', '<div>The block has totally new content now!!!' . $user_name . ' </div>');
    $response->addCommand($command);
    return $response;

  }

}
