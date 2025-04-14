<?php

namespace Drupal\event_api_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\event_api_block\NotificationApiClient;

/**
 * Provides a block to show notifications from the microservice.
 *
 * @Block(
 *   id = "notification_block",
 *   admin_label = @Translation("Notification Block")
 * )
 */
class NotificationBlock extends BlockBase implements ContainerFactoryPluginInterface {

  protected $notificationClient;

  /**
   * Constructor for the block.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, NotificationApiClient $notification_client) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->notificationClient = $notification_client;
  }

  /**
   * Used by Drupal's dependency injection to pass the NotificationApiClient.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('event_api_block.notification_client')
    );
  }

  /**
   * Build the block content.
   */
  public function build() {
    $notifications = $this->notificationClient->fetchNotifications();

    if (empty($notifications)) {
      $items = ['#markup' => $this->t('No notifications found.')];
    } else {
      $items = [
        '#theme' => 'item_list',
        '#items' => array_map(function ($note) {
          return "🔔 {$note['message']} at {$note['time']}";
        }, $notifications),
      ];
    }
    $items['#cache'] = ['max-age' => 0];
    return $items;
  }
}
