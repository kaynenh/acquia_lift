<?php

/**
 * @file
 * Contains \Drupal\acquia_lift\Tests\Traits\SettingsDataTrait.
 */

namespace Drupal\acquia_lift\Tests\Traits;

/**
 * Settings Data Trait.
 */
trait SettingsDataTrait {
  /**
   * Get a valid credential settings array.
   *
   * @return array
   *   A valid credential settings array.
   */
  private function getValidCredentialSettings() {
    return [
      'account_name' => 'account_name_1',
      'customer_site' => 'customer_site_1',
      'api_url' => 'api_url_1',
      'access_key' => 'access_key_1',
      'secret_key' => 'secret_key_1',
      'js_path' => 'js_path_1',
    ];
  }

  /**
   * Get a valid identity settings array.
   *
   * @return array
   *   A valid identity settings array.
   */
  private function getValidIdentitySettings() {
    return [
//      'capture_identity' => FALSE,
      'identity_parameter' => 'my_identity_parameter',
      'identity_type_parameter' => 'my_identity_type_parameter',
      'default_identity_type' => 'my_default_identity_type',
    ];
  }

  /**
   * Get a valid field mappings settings array.
   *
   * @return array
   *   A valid field mappings settings array.
   */
  private function getValidFieldMappingsSettings() {
    return [
      'content_section' => 'field_country',
      'content_keywords' => 'field_tags',
      'persona' => 'field_people',
    ];
  }

  /**
   * Get a valid thumbnail settings array.
   *
   * @return array
   *   A valid thumbnail settings array.
   */
  private function getValidThumbnailSettings() {
    return [
      'article' => [
        'field' => 'field_media->field_image',
        'style' => 'medium',
      ],
    ];
  }

  /**
   * Get a valid visibility settings array.
   *
   * @return array
   *   A valid visibility settings array.
   */
  private function getValidVisibilitySettings() {
    return [
      'path_patterns' => 'my_path_pattern',
    ];
  }

  /**
   * Get a valid front end credential settings.
   *
   * @return array
   *   A valid front end credential settings array.
   */
  private function getValidFrontEndCredentialSettings() {
    return [
      'account_name' => 'account_name_1',
      'customer_site' => 'customer_site_1',
      'js_path' => 'js_path_1',
    ];
  }

  /**
   * Convert to post form settings.
   *
   * @param array $settings
   * @param string $prefix
   *
   * @return array
   *   A valid front end credential settings array.
   */
  private function convertToPostFormSettings($settings, $prefix) {
    $post_form_settings = [];
    foreach ($settings as $setting_name => $setting_value) {
      $post_form_settings[$prefix . '[' . $setting_name . ']'] = $setting_value;
    }
    return $post_form_settings;
  }

  /**
   * Convert strings with underscores into CamelCase
   *
   * @param    string    $string    The string to convert
   * @param    bool    $first_char_caps    camelCase or CamelCase
   *
   * @return    string    The converted string
   */
//  function underscoreToCamelCase( $string, $first_char_caps = false) {
//    if( $first_char_caps == true )
//    {
//        $string[0] = strtoupper($string[0]);
//    }
//    $func = create_function('$c', 'return strtoupper($c[1]);');
//    return preg_replace_callback('/_([a-z])/', $func, $string);
//  }
}