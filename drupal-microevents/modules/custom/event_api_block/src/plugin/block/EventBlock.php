<?php

namespace Drupal\event_api_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\event_api_block\EventApiClient;

/**
 * Provides a block that displays events from an external API.
 *
 * @Block(
 *   id = "event_api_block",
 *   admin_label = @Translation("Event API Block")
 * )
 */
class EventBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $apiClient;

  public function __construct(array $configuration, $plugin_id, $plugin_definition, EventApiClient $api_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->apiClient = $api_client;
  }

  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('event_api_block.api_client')
    );
  }

  public function build() {
    $events = $this->apiClient->fetchEvents();
    $items = [];

    foreach ($events as $event) {
      $items[] = [
        '#markup' => "<strong>{$event['title']}</strong> - {$event['date']} @ {$event['location']}",
        '#cache' => [
          'max-age' => 0,
        ],
      ];
    }

    return [
      '#theme' => 'item_list',
      '#items' => $items,
      '#title' => $this->t('Upcoming Events'),
    ];
  }
}