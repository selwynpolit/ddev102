<?php

namespace Drupal\leafmap\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\leaflet\LeafletService;

/**
 * Provides a 'LeafMapBlock' block.
 *
 * @Block(
 *  id = "leaf_map_block",
 *  admin_label = @Translation("Leaf map block"),
 * )
 */
class LeafMapBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Drupal\leaflet\LeafletService definition.
   *
   * @var \Drupal\leaflet\LeafletService
   */
  protected $leafletService;

  /**
   * Constructs a new LeafMapBlock object.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, LeafletService $leaflet_service) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->leafletService = $leaflet_service;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('leaflet.service')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $build = [];

    // Define your points.
    $points = [
      ['lat' => 37.7749, 'lon' => -122.4194, 'city' => 'San Francisco', 'job' => 'Software Engineer'],
      ['lat' => 34.0522, 'lon' => -118.2437, 'city' => 'Los Angeles', 'job' => 'Data Analyst'],
      ['lat' => 36.746841, 'lon' => -119.772591, 'city' => 'Fresno', 'job' => 'Web Developer'],
      ['lat' => 30.2672, 'lon' => -97.7431, 'city' => 'Austin', 'job' => 'Software Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Software Engineer'],


      // Add more points as needed.
    ];

    // Convert points to features.
    $features = [];
    foreach ($points as $point) {
      // Internal link.
      //$link = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => 1])->toString();
      //$popupContent = $point['city'] . '<br>Lat: ' . $point['lat'] . '<br>Lon: ' . $point['lon'] . '<br>Job: ' . $point['job'] . '<br><a href="' . $link . '">More info</a>';

      // External link.
      $url = \Drupal\Core\Url::fromUri('https://www.nytimes.com');
      // Set options to open link in new window.
      $url->setOptions(['attributes' => ['target' => '_blank']]);
      $link = \Drupal\Core\Link::fromTextAndUrl('More info', $url);
      $popupContent = $point['city'] . '<br>Lat: ' . $point['lat'] . '<br>Lon: ' . $point['lon'] . '<br>Job: ' . $point['job'] . '<br>' . $link->toString();

      $features[] = [
        'type' => 'point',
        'lat' => $point['lat'],
        'lon' => $point['lon'],
        'popup' => [
          'value' => $popupContent,
          ],
      ];
    }

    // Create a map with the features.
    $map = leaflet_map_get_info('OSM Mapnik');
    $build['map'] = $this->leafletService->leafletRenderMap($map, $features, '600px');
    //$build['map'] = $this->leafletService->leafletRenderMap($map, $features, '100%');

    // Attach the library.
    $build['#attached']['library'][] = 'leafmap/leaflet-popup';

    return $build;
  }

}
