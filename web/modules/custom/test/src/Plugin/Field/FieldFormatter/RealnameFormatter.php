<?php

declare(strict_types=1);

namespace Drupal\test\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Plugin implementation of the 'Real Name Formatter' formatter.
 *
 * @FieldFormatter(
 *   id = "realname_one_line",
 *   label = @Translation("Real Name Formatter (one line)"),
 *   field_types = {"realname"},
 * )
 */
final class RealnameFormatter extends FormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode): array {
    $element = [];
    foreach ($items as $delta => $item) {
      $element[$delta] = [
        //'#markup' => $item->value,
        '#markup' => $this->t('@first_name @last_name', [
          '@first_name' => $item->first_name,
          '@last_name' => $item->last_name,
      ]),
      ];
    }
    return $element;
  }

}
