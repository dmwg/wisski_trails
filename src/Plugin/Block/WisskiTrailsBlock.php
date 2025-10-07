<?php

namespace Drupal\wisski_trails\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\wisski_trails\Service\WisskiTrailsService;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a WissKI Trails block.
 *
 * @\Drupal\Core\Block\Annotation\Block(
 *   id = "wisski_trails_block",
 *   admin_label = @Translation("WissKI Trails"),
 *   category = @Translation("WissKI")
 * )
 *
 * @phpstan-consistent-constructor
 */
class WisskiTrailsBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The WissKI Trails service.
   *
   * @var \Drupal\wisski_trails\Service\WisskiTrailsService
   */
  protected $wisskiTrailsService;

  /**
   * The logger.
   *
   * @var \Psr\Log\LoggerInterface
   */
  protected $logger;

  /**
   * Constructs a new WisskiTrailsBlock instance.
   *
   * @param array $configuration
   *   The plugin configuration.
   * @param string $plugin_id
   *   The plugin ID.
   * @param mixed $plugin_definition
   *   The plugin definition.
   * @param \Drupal\wisski_trails\Service\WisskiTrailsService $wisski_trails_service
   *   The WissKI Trails service.
   * @param \Psr\Log\LoggerInterface $logger
   *   The logger.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, WisskiTrailsService $wisski_trails_service, LoggerInterface $logger) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->wisskiTrailsService = $wisski_trails_service;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    /** @var \Drupal\wisski_trails\Service\WisskiTrailsService $service */
    $service = $container->get('wisski_trails.service');
    /** @var \Psr\Log\LoggerInterface $logger */
    $logger = $container->get('logger.channel.wisski_trails');

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $service,
      $logger
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $entity_id = $this->wisskiTrailsService->getCurrentEntityId();

    if (!$entity_id) {
      $this->logger->warning('No entity ID found for WissKI Trails block.');
      return [];
    }

    $iframe_url = $this->wisskiTrailsService->buildIframeUrl($entity_id);

    if (!$iframe_url) {
      $this->logger->error('Could not build iframe URL for entity @id.', ['@id' => $entity_id]);
      return [];
    }

    return [
      '#theme' => 'wisski_trails_iframe',
      '#iframe_url' => $iframe_url,
      '#entity_id' => $entity_id,
      '#should_display' => $this->wisskiTrailsService->shouldDisplay(),
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

}
