<?php

declare(strict_types=1);

namespace Drupal\serviceplay\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Serviceplay routes.
 */
final class ServiceplayController extends ControllerBase {

  /**
   * Messenger service.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The controller constructor.
   */
  public function __construct(
    MessengerInterface $messenger,
  ) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container): self {
    return new self(
      $container->get('messenger'),
    );
  }

  /**
   * Builds the response.
   */
  public function __invoke(): array {

    $service_example = \Drupal::service('serviceplay.example');
    $string = $service_example->getSomething();

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('I got this from the service: @string', ['@string' => $string]),
    ];

    return $build;
  }

}
