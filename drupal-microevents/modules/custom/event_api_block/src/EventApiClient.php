<?php

namespace Drupal\event_api_block;

use GuzzleHttp\ClientInterface;

class EventApiClient {
  protected $httpClient;

  public function __construct(ClientInterface $http_client) {
    $this->httpClient = $http_client;
  }

  public function fetchEvents() {
    try {
      $response = $this->httpClient->request('GET', 'http://host.docker.internal:8001/api/events.php', [
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
