<?php

/**
 * @file
 * Contains \Drupal\acquia_lift\Service\Api\DataApi.
 */

namespace Drupal\acquia_lift\Service;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Path\CurrentPathStack;
use Drupal\Core\Path\AliasManager;
use Drupal\Core\Path\PathMatcher;
use Drupal\Component\Utility\Unicode;
use Drupal\acquia_lift\Entity\Credential;

class PageAttachmentsManager {
  /**
   * Alias manager.
   *
   * @var \Drupal\Core\Path\AliasManager
   */
  private $aliasManager;

  /**
   * Path matcher.
   *
   * @var \Drupal\Core\Path\PathMatcher
   */
  private $pathMatcher;

  /**
   * Acquia Lift credential.
   *
   * @var \Drupal\acquia_lift\Entity\Credential
   */
  private $credential;

  /**
   * Page context.
   *
   * @var array
   *
   * @todo: pageContext should be its own service.
   */
  private $pageContext =  array(
    'content_title' => 'Untitled',
    'content_type' => 'page',
    'page_type' => 'content page',
    'content_section' => '',
    'content_keywords' => '',
    'post_id' => '',
    'published_date' => '',
    'thumbnail_url' => '',
    'persona' => '',
    'engagement_score' => '1',
    'author' => '',
    'evalSegments' => TRUE,
    'trackingId' => '',
  );

  /**
   * Visibility.
   *
   * @var array
   */
  private $visibility;

  /**
   * Current path.
   *
   * @var string
   *
   * @todo: currentPath, aliasManager, and pathMatcher should be extracted to a new service.
   */
  private $currentPath;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *   The config factory service.
   * @param \Drupal\Core\Path\CurrentPathStack $current_path_stack
   *   The current path service.
   * @param \Drupal\Core\Path\AliasManager $alias_manager
   *   The alias manager service.
   * @param \Drupal\Core\Path\PathMatcher $path_matcher
   *   The path matcher service.
   */
  public function __construct(ConfigFactory $config_factory, CurrentPathStack $current_path_stack, AliasManager $alias_manager, PathMatcher $path_matcher) {
    $settings = $config_factory->get('acquia_lift.settings');
    $credential_settings = $settings->get('credential');
    $this->credential = new Credential($credential_settings);
    $this->visibility = $settings->get('visibility');
    $this->currentPath = $current_path_stack->getPath();
    $this->aliasManager = $alias_manager;
    $this->pathMatcher = $path_matcher;
  }

  /**
   * Should attach.
   *
   * @return boolean
   *   True if should attach.
   */
  public function shouldAttach() {
    // Credential need to be filled.
    if (!$this->credential->isValid()) {
      return FALSE;
    }

    // Current path cannot match the path patterns.
    if ($this->matchRequestPath()) {
      return FALSE;
    }

    return TRUE;
  }

  /**
   * Determine if the request path falls into one of the allowed paths.
   *
   * @return boolean
   *   True if should attach.
   */
  private function matchRequestPath() {
    // Convert path to lowercase and match.
    $path_patterns = Unicode::strtolower($this->visibility['request_path_pages']);
    if ($this->pathMatcher->matchPath($this->currentPath, $path_patterns)) {
      return TRUE;
    }

    // Compare the lowercase path alias (if any) and internal path.
    $path_alias = Unicode::strtolower($this->aliasManager->getAliasByPath($this->currentPath));
    if (($this->currentPath != $path_alias) && $this->pathMatcher->matchPath($path_alias, $path_patterns)) {
      return TRUE;
    }

    return FALSE;
  }

  /**
   * Get Drupal JavaScript settings.
   *
   * @return array
   *   Settings.
   */
  public function getDrupalSettings() {
    $settings['credential'] = $this->credential->toArray();
    $settings['pageContext'] = $this->pageContext;
//    $settings['identity'] = array();

    return $settings;
  }

  /**
   * Set page context.
   *
   * @param \Drupal\Core\Entity\EntityInterface $node
   *   Node.
   */
  public function setPageContext(EntityInterface $node) {
    $this->pageContext['content_type'] = $node->getType();
    $this->pageContext['content_title'] = $node->getTitle();
    $this->pageContext['published_date'] = $node->getCreatedTime();
    $this->pageContext['post_id'] = $node->id();
    $this->pageContext['author'] = $node->getOwner()->getUsername();
    $this->pageContext['page_type'] = 'node page';
    //@todo: this needs to be converted to a proper thumbnail_url.
    $this->pageContext['thumbnail_url'] = $node->field_image->entity->url();
  }

  /**
   * Get library.
   *
   * @return string
   *   Library identifier.
   */
  public function getLibrary() {
    return 'acquia_lift/acquia_lift';
  }
}