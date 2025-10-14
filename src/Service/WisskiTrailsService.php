<?php

namespace Drupal\wisski_trails\Service;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Psr\Log\LoggerInterface;

/**
 * Service for WissKI Trails functionality.
 */
class WisskiTrailsService {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a WisskiTrailsService object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   *
   * @codeCoverageIgnore
   */
  public function __construct(ConfigFactoryInterface $config_factory, RouteMatchInterface $route_match, LoggerInterface $logger) {
    $this->configFactory = $config_factory;
    $this->routeMatch = $route_match;
    $this->logger = $logger;
  }

  /**
   * Gets the current entity ID from the route.
   *
   * @return string|null
   *   The entity ID or NULL if not found.
   */
  public function getCurrentEntityId(): ?string {
    $entity = $this->getEntityFromRoute();
    if ($entity) {
      return $entity->id();
    }

    $this->logger->warning('No entity found in current route.');
    return NULL;
  }

  /**
   * Gets the entity from the current route.
   *
   * @return \Drupal\Core\Entity\EntityInterface|null
   *   The entity or NULL if not found.
   */
  protected function getEntityFromRoute(): ?EntityInterface {
    foreach ($this->routeMatch->getParameters() as $parameter) {
      if ($parameter instanceof EntityInterface) {
        return $parameter;
      }
    }
    return NULL;
  }

  /**
   * Builds the iframe URL.
   *
   * @param string $entity_id
   *   The entity ID.
   *
   * @return string|null
   *   The iframe URL or NULL if base_url is not configured.
   */
  public function buildIframeUrl($entity_id): ?string {
    $config = $this->configFactory->get('wisski_trails.settings');
    $base_url = $config->get('base_url');

    if (empty($base_url)) {
      $this->logger->error('Base URL is not configured.');
      return NULL;
    }

    return rtrim($base_url, '/') . '/' . $entity_id . '.html';
  }

  /**
   * Checks if the block should be displayed.
   *
   * @return bool
   *   TRUE if the block should be displayed, FALSE otherwise.
   */
  public function shouldDisplay(): bool {
    $entity_id = $this->getCurrentEntityId();
    $config = $this->configFactory->get('wisski_trails.settings');
    $base_url = $config->get('base_url');

    return !empty($entity_id) && !empty($base_url);
  }

}
