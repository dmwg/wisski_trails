<?php

namespace Drupal\Tests\wisski_trails\Unit;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Config\ImmutableConfig;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\wisski_trails\Service\WisskiTrailsService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\Route;

/**
 * Tests the WisskiTrailsService.
 *
 * @coversDefaultClass \Drupal\wisski_trails\Service\WisskiTrailsService
 * @group wisski_trails
 */
class WisskiTrailsServiceTest extends TestCase {

  /**
   * The mocked config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $configFactory;

  /**
   * The mocked route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $routeMatch;

  /**
   * The mocked logger.
   *
   * @var \Psr\Log\LoggerInterface|\PHPUnit\Framework\MockObject\MockObject
   */
  protected $logger;

  /**
   * The service under test.
   *
   * @var \Drupal\wisski_trails\Service\WisskiTrailsService
   */
  protected $service;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    $this->configFactory = $this->createMock(ConfigFactoryInterface::class);
    $this->routeMatch = $this->createMock(RouteMatchInterface::class);
    $this->logger = $this->createMock(LoggerInterface::class);

    $this->service = new WisskiTrailsService(
      $this->configFactory,
      $this->routeMatch,
      $this->logger
    );
  }

  /**
   * Tests getCurrentEntityId() with an entity in the route.
   *
   * @covers ::getCurrentEntityId
   * @covers ::getEntityFromRoute
   */
  public function testGetCurrentEntityIdWithEntity(): void {
    $entity = $this->createMock(EntityInterface::class);
    $entity->expects($this->once())
      ->method('id')
      ->willReturn('42');

    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag(['entity' => $entity, 'other' => 'value']));

    $result = $this->service->getCurrentEntityId();
    $this->assertEquals('42', $result);
  }

  /**
   * Tests getCurrentEntityId() without an entity in the route.
   *
   * @covers ::getCurrentEntityId
   * @covers ::getEntityFromRoute
   */
  public function testGetCurrentEntityIdWithoutEntity(): void {
    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag(['other' => 'value']));

    $this->logger->expects($this->once())
      ->method('warning')
      ->with('No entity found in current route.');

    $result = $this->service->getCurrentEntityId();
    $this->assertNull($result);
  }

  /**
   * Tests getCurrentEntityId() with empty parameters.
   *
   * @covers ::getCurrentEntityId
   * @covers ::getEntityFromRoute
   */
  public function testGetCurrentEntityIdWithEmptyParameters(): void {
    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag([]));

    $this->logger->expects($this->once())
      ->method('warning')
      ->with('No entity found in current route.');

    $result = $this->service->getCurrentEntityId();
    $this->assertNull($result);
  }

  /**
   * Tests buildIframeUrl() with valid base URL and entity ID.
   *
   * @covers ::buildIframeUrl
   */
  public function testBuildIframeUrlWithValidData(): void {
    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('https://example.com/viz');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $result = $this->service->buildIframeUrl('42');
    $this->assertEquals('https://example.com/viz/42', $result);
  }

  /**
   * Tests buildIframeUrl() with trailing slash in base URL.
   *
   * @covers ::buildIframeUrl
   */
  public function testBuildIframeUrlWithTrailingSlash(): void {
    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('https://example.com/viz/');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $result = $this->service->buildIframeUrl('42');
    $this->assertEquals('https://example.com/viz/42', $result);
  }

  /**
   * Tests buildIframeUrl() with empty base URL.
   *
   * @covers ::buildIframeUrl
   */
  public function testBuildIframeUrlWithEmptyBaseUrl(): void {
    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $this->logger->expects($this->once())
      ->method('error')
      ->with('Base URL is not configured.');

    $result = $this->service->buildIframeUrl('42');
    $this->assertNull($result);
  }

  /**
   * Tests shouldDisplay() with valid entity and base URL.
   *
   * @covers ::shouldDisplay
   */
  public function testShouldDisplayWithValidData(): void {
    $entity = $this->createMock(EntityInterface::class);
    $entity->expects($this->once())
      ->method('id')
      ->willReturn('42');

    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag(['entity' => $entity]));

    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('https://example.com/viz');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $result = $this->service->shouldDisplay();
    $this->assertTrue($result);
  }

  /**
   * Tests shouldDisplay() without entity.
   *
   * @covers ::shouldDisplay
   */
  public function testShouldDisplayWithoutEntity(): void {
    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag([]));

    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('https://example.com/viz');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $this->logger->expects($this->once())
      ->method('warning');

    $result = $this->service->shouldDisplay();
    $this->assertFalse($result);
  }

  /**
   * Tests shouldDisplay() without base URL.
   *
   * @covers ::shouldDisplay
   */
  public function testShouldDisplayWithoutBaseUrl(): void {
    $entity = $this->createMock(EntityInterface::class);
    $entity->expects($this->once())
      ->method('id')
      ->willReturn('8734');

    $this->routeMatch->expects($this->once())
      ->method('getParameters')
      ->willReturn(new ParameterBag(['entity' => $entity]));

    $config = $this->createMock(ImmutableConfig::class);
    $config->expects($this->once())
      ->method('get')
      ->with('base_url')
      ->willReturn('');

    $this->configFactory->expects($this->once())
      ->method('get')
      ->with('wisski_trails.settings')
      ->willReturn($config);

    $result = $this->service->shouldDisplay();
    $this->assertFalse($result);
  }

}
