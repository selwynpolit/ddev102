<?php

declare(strict_types=1);

namespace Drupal\test\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'realname' field type.
 *
 * @FieldType(
 *   id = "realname",
 *   label = @Translation("RealName"),
 *   description = @Translation("Real name - includes first and last."),
 *   category = @Translation("General"),
 *   default_widget = "realname_default",
 *   default_formatter = "realname_one_line",
 * )
 */
final class Realname extends FieldItemBase {

  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition): array {

    $columns = [
      'first_name' => [
        'type' => 'varchar',
        'description' => 'first name.',
        'length' => 255,
        'not null' => TRUE,
        'default' => '',
      ],
      'last_name' => [
        'type' => 'varchar',
        'description' => 'last name.',
        'length' => 255,
        'not null' => TRUE,
        'default' => '',
      ],
    ];

    $schema = [
      'columns' => $columns,
      'indexes' => [
        'first_name' => ['first_name'],
        'last_name' => ['last_name'],
      ],
    ];

    return $schema;
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition): array {

    // @DCG
    // See /core/lib/Drupal/Core/TypedData/Plugin/DataType directory for
    // available data types.
    $properties['first_name'] = DataDefinition::create('string')
      ->setLabel(t('First name'));
      //->setRequired(TRUE);
    $properties['last_name'] = DataDefinition::create('string')
      ->setLabel(t('Last name'));
      //->setRequired(TRUE);
    return $properties;
  }

  /**
   * {@inheritdoc}
   */
  public static function mainPropertyName() {
    return 'first_name';
  }


  /**
   * {@inheritdoc}
   */
  public function getConstraints(): array {
    $constraints = parent::getConstraints();

    $constraint_manager = $this->getTypedDataManager()->getValidationConstraintManager();

    // @DCG Suppose our value must not be longer than 10 characters.
    $options['first_name']['Length']['max'] = 10;

    // @DCG
    // See /core/lib/Drupal/Core/Validation/Plugin/Validation/Constraint
    // directory for available constraints.
    $constraints[] = $constraint_manager->create('ComplexData', $options);
    return $constraints;
  }

  /**
   * {@inheritdoc}
   */
  public static function generateSampleValue(FieldDefinitionInterface $field_definition): array {
    $random = new Random();
    $values['first_name'] = $random->word(mt_rand(1, 50));
    $values['last_name'] = $random->word(mt_rand(1, 50));
    return $values;
  }

  /**
   * {@inheritdoc}
   */
  public function isEmpty(): bool {
    $first_name = $this->get('first_name')->getValue();
    $last_name = $this->get('last_name')->getValue();
    if (empty($first_name) && empty($last_name)) {
      return TRUE;
    }
    return FALSE;
  }


}
