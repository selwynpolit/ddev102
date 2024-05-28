<?php

declare(strict_types=1);

namespace Drupal\route_play;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslatableMarkup;

/**
 * Salutation to the world.
 */
final class HelloWorldSalutation {
  use StringTranslationTrait;

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected ConfigFactoryInterface $configFactory;

  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
  }

  /**
   * Get a salutation based on the time of day.
   *
   * @return \Drupal\Core\StringTranslation\TranslatableMarkup
   */
  public function getSalutation(): TranslatableMarkup {

    //$salutation = \Drupal::config('route_play.settings')->get('salutation');

    $config = $this->configFactory->get('route_play.settings');
    $salutation = $config->get('salutation');

    $time = new \DateTime('now', new \DateTimeZone('AMERICA/CHICAGO'));
    if ((int) $time->format('G') >= 00 && (int) $time->format('G') < 12) {
      return $this->t('Good morning,' . $salutation . ' world');
    }
    if ((int) $time->format('G') >= 12 && (int) $time->format('G') < 18) {
      return $this->t('Good afternoon,' . $salutation . ' world');
    }
    if ((int) $time->format('G') >= 18) {
      return $this->t('Good evening, ' . $salutation . ' world');
    }
  }

}
