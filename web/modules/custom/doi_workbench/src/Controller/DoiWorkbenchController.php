<?php

declare(strict_types=1);

namespace Drupal\doi_workbench\Controller;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for Doi workbench routes.
 */
final class DoiWorkbenchController extends ControllerBase {

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
  public function ajaxCallback(): AjaxResponse {
    $response = new AjaxResponse();

    //$response->addCommand(new HtmlCommand('#menu-listing-ajax-wrapper', 'Hello, World!'));
    $response->addCommand(new HtmlCommand('.my-menu-list', 'Hello, World!'));
    return $response;
  }

}
