<?php

namespace Drupal\event_api_block;

use GuzzleHttp\ClientInterface;

class NotificationApiClient {
  protected $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function fetchNotifications() {
    try {
      $response = $this->httpClient->request('GET', 'http://host.docker.internal:8002/api/notifications.php', [
        'headers' => [
          'Authorization' => 'Bearer supersecrettoken',
        ],
      ]);
      return json_decode($response->getBody(), true);
    } catch (\Exception $e) {
      \Drupal::logger('event_api_block')->error($e->getMessage());
      return [];
    }
  }
}
