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

    $points = $this->getPoints();

    if (TRUE) {
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

      // Turn on clustering.
      $map['settings']['leaflet_markercluster']['control'] = TRUE;

      $build['map'] = $this->leafletService->leafletRenderMap($map, $features, '600px');
      //$build['map'] = $this->leafletService->leafletRenderMap($map, $features, '100%');

      // Attach the library.
      $build['#attached']['library'][] = 'leafmap/leaflet-popup';

      return $build;
    }
  }


  protected function getPoints(): array {
    // Define your points.
    $points = [
      ['lat' => 37.7749, 'lon' => -122.4194, 'city' => 'San Francisco', 'job' => 'Software Engineer'],
      ['lat' => 34.0522, 'lon' => -118.2437, 'city' => 'Los Angeles', 'job' => 'Data Analyst'],
      ['lat' => 36.746841, 'lon' => -119.772591, 'city' => 'Fresno', 'job' => 'Web Developer'],
      ['lat' => 30.2672, 'lon' => -97.7431, 'city' => 'Austin', 'job' => 'Software Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Software Engineer'],

      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Data Scientist'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'UI/UX Designer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Mobile Developer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Product Manager'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Project Manager'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'DevOps Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Database Administrator'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Cloud Solutions Architect'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Cybersecurity Specialist'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Blockchain Developer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'AI Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Machine Learning Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Network Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Systems Analyst'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Technical Support Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Quality Assurance Engineer'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'SEO Specialist'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Content Strategist'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'IT Consultant'],
      ['lat' => 40.7128, 'lon' => -74.0060, 'city' => 'New York', 'job' => 'Software Architect'],
    ];

    $jobsInNearbyCities = [
      ['lat' => 40.7450, 'lon' => -73.9486, 'city' => 'Long Island City', 'job' => 'Frontend Developer'],
      ['lat' => 40.9263, 'lon' => -74.0770, 'city' => 'Jersey City', 'job' => 'Backend Developer'],
      ['lat' => 40.8568, 'lon' => -73.9310, 'city' => 'Bronx', 'job' => 'Full Stack Developer'],
      ['lat' => 40.6501, 'lon' => -73.9496, 'city' => 'Brooklyn', 'job' => 'DevOps Specialist'],
      ['lat' => 40.6635, 'lon' => -73.9387, 'city' => 'Brooklyn', 'job' => 'Data Analyst'],
      ['lat' => 40.5795, 'lon' => -74.1502, 'city' => 'Staten Island', 'job' => 'Machine Learning Specialist'],
      ['lat' => 40.8373, 'lon' => -73.8860, 'city' => 'Bronx', 'job' => 'Cloud Engineer'],
      ['lat' => 40.7282, 'lon' => -74.0776, 'city' => 'Jersey City', 'job' => 'Network Administrator'],
      ['lat' => 40.6782, 'lon' => -73.9442, 'city' => 'Brooklyn', 'job' => 'Cybersecurity Analyst'],
      ['lat' => 40.8448, 'lon' => -73.8648, 'city' => 'Bronx', 'job' => 'Blockchain Expert'],
      ['lat' => 40.7433, 'lon' => -73.9180, 'city' => 'Long Island City', 'job' => 'AI Researcher'],
      ['lat' => 40.5795, 'lon' => -74.1502, 'city' => 'Staten Island', 'job' => 'Systems Engineer'],
      ['lat' => 40.7282, 'lon' => -74.0776, 'city' => 'Jersey City', 'job' => 'IT Support Specialist'],
      ['lat' => 40.6501, 'lon' => -73.9496, 'city' => 'Brooklyn', 'job' => 'Software Tester'],
      ['lat' => 40.6782, 'lon' => -73.9442, 'city' => 'Brooklyn', 'job' => 'User Experience Designer'],
      ['lat' => 40.7433, 'lon' => -73.9180, 'city' => 'Long Island City', 'job' => 'Mobile App Developer'],
      ['lat' => 40.8448, 'lon' => -73.8648, 'city' => 'Bronx', 'job' => 'SEO Manager'],
      ['lat' => 40.9263, 'lon' => -74.0770, 'city' => 'Jersey City', 'job' => 'Content Manager'],
      ['lat' => 40.8373, 'lon' => -73.8860, 'city' => 'Bronx', 'job' => 'Digital Marketing Specialist'],
    ];
    $points = array_merge($points, $jobsInNearbyCities);
    return $points;
  }

}
