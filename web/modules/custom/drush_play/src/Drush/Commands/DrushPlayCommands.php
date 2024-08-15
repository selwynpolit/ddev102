<?php

namespace Drupal\drush_play\Drush\Commands;

use Consolidation\OutputFormatters\StructuredData\RowsOfFields;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\Sql\QueryFactory;
use Drupal\Core\Logger\LoggerChannelInterface;
use Drupal\Core\Utility\Token;
use Drush\Attributes as CLI;
use Drush\Commands\DrushCommands;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * A Drush commandfile.
 */
final class DrushPlayCommands extends DrushCommands {

  private EntityFieldManagerInterface $entityFieldManager;

  /**
   * Constructs a DrushPlayCommands object.
   */
  public function __construct(
    private readonly Token $token,
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly QueryFactory $entityQuerySql,
    private readonly LoggerChannelInterface $loggerChannelDefault,
    EntityFieldManagerInterface $entityFieldManager
  ) {
    parent::__construct();
    $this->entityFieldManager = $entityFieldManager;

  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('token'),
      $container->get('entity_type.manager'),
      $container->get('entity.query.sql'),
      $container->get('logger.channel.default'),
      $container->get('entity_field.manager')
    );
  }

  /**
   * Command description here.
   */
  #[CLI\Command(name: 'drush_play:command-name', aliases: ['foo1'])]
  #[CLI\Argument(name: 'arg1', description: 'Argument description.')]
  #[CLI\Option(name: 'option-name', description: 'Option description')]
  #[CLI\Usage(name: 'drush_play:command-name foo', description: 'Usage description')]
  public function commandName($arg1, $options = ['option-name' => 'default']) {
    $this->logger()->success(dt('Achievement unlocked.'));
  }


  /**
   * Finds content types that use the "button" paragraph type.
   */
  #[CLI\Command(name: 'drush_play:find-button-usage', aliases: ['fbu'])]
  #[CLI\Description(description: 'Finds content types that use the "button" paragraph type.')]
  public function findButtonUsage(): RowsOfFields {
    $contentTypes = $this->entityTypeManager->getStorage('node_type')->loadMultiple();
    $rows = [];

      foreach ($contentTypes as $contentType) {
        // Use the entityFieldManager to get field definitions
      $fields = $this->entityFieldManager->getFieldDefinitions('node', $contentType->id());
      foreach ($fields as $field) {
        if ($field->getType() === 'entity_reference_revisions') {
          $settings = $field->getSettings();
          if (isset($settings['target_type']) && $settings['target_type'] === 'paragraph') {
            $allowedTypes = $settings['handler_settings']['target_bundles'] ?? [];
            if (in_array('button', $allowedTypes)) {
              $rows[] = [
                'content_type' => $contentType->id(),
                'field_name' => $field->getName(),
              ];
            }
          }
        }
      }
    }

    if (empty($rows)) {
      $this->logger()->warning(dt('No content types found using the "button" paragraph type.'));
      return new RowsOfFields([]);
    }

    return new RowsOfFields($rows);
  }

  /**
   * Lists content types using a specified paragraph and URLs to the most recent 5 nodes of each type.
   */
  #[CLI\Command(name: 'drush_play:list-usage', aliases: ['lpu'])]
  #[CLI\Description(description: 'Lists content types using a specified paragraph and URLs to the most recent 5 nodes of each type.')]
  #[CLI\Argument(name: 'paragraph_machine_name', description: 'The machine name of the paragraph type.')]
  public function listUsage($paragraph_machine_name): RowsOfFields {
    $contentTypes = $this->entityTypeManager->getStorage('node_type')->loadMultiple();
    $rows = [];
    $nodeUrls = [];

    foreach ($contentTypes as $contentType) {
      $fields = $this->entityFieldManager->getFieldDefinitions('node', $contentType->id());
      foreach ($fields as $field) {
        if ($field->getType() === 'entity_reference_revisions') {
          $settings = $field->getSettings();
          if (isset($settings['target_type']) && $settings['target_type'] === 'paragraph') {
            $allowedTypes = $settings['handler_settings']['target_bundles'] ?? [];
            if (in_array($paragraph_machine_name, $allowedTypes)) {
              $rows[] = [
                'content_type' => $contentType->id(),
                'field_name' => $field->getName(),
              ];

              // Query for the most recent 5 nodes of this content type using the paragraph
              $content_types = [];
              foreach ($rows as $row) {
                if (!in_array($row['content_type'], $content_types)) {
                  $content_types[] = $row['content_type'];
                }
              }

              $nodeStorage = $this->entityTypeManager->getStorage('node');
              $query = $nodeStorage->getQuery()
                ->condition('type', $content_types, 'IN')
                //->condition($field->getName() . '.target_id', $paragraph_machine_name, '=')
                ->range(0, 5)
                ->accessCheck(FALSE)
                ->sort('created', 'DESC');
              $nids = $query->execute();

              foreach ($nids as $nid) {
                $nodeUrls[$contentType->id()][] = \Drupal::request()->getSchemeAndHttpHost() . '/node/' . $nid;
              }
            }
          }
        }
      }
    }

    if (empty($rows)) {
      $this->logger()->warning(dt('No content types found using the specified paragraph type.'));
      return new RowsOfFields([]);
    }

    // Output the content types and URLs
    foreach ($rows as &$row) {
      $contentType = $row['content_type'];
      $row['recent_nodes'] = implode(', ', $nodeUrls[$contentType] ?? []);
    }

    return new RowsOfFields($rows);
  }

  #[CLI\Command(name: 'drush_play:list-paragraph-usage', aliases: ['lpu'])]
  #[CLI\Description(description: 'Lists paragraph types that use a specified paragraph type.')]
  #[CLI\Argument(name: 'paragraph_machine_name', description: 'The machine name of the paragraph type to search for.')]
  public function listParagraphUsage($paragraph_machine_name): RowsOfFields {
    $paragraphTypes = $this->entityTypeManager->getStorage('paragraphs_type')->loadMultiple();
    $rows = [];

    foreach ($paragraphTypes as $paragraphType) {
      $fields = $this->entityFieldManager->getFieldDefinitions('paragraph', $paragraphType->id());
      foreach ($fields as $field) {
        if ($field->getType() === 'entity_reference_revisions') {
          $settings = $field->getSettings();
          if (isset($settings['target_type']) && $settings['target_type'] === 'paragraph') {
            $allowedTypes = $settings['handler_settings']['target_bundles'] ?? [];
            if (in_array($paragraph_machine_name, $allowedTypes)) {
              $rows[] = [
                'paragraph_type' => $paragraphType->id(),
                'field_name' => $field->getName(),
              ];
            }
          }
        }
      }
    }

    if (empty($rows)) {
      $this->logger()->warning(dt('No paragraph types found using the specified paragraph type.'));
      return new RowsOfFields([]);
    }

    return new RowsOfFields($rows);
  }



}
