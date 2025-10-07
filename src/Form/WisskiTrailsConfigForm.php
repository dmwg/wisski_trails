<?php

declare(strict_types=1);

namespace Drupal\wisski_trails\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure WissKI Trails settings.
 *
 * @codeCoverageIgnore This is just a thin wrapper around well-tested Drupal logic.
 */
class WisskiTrailsConfigForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['wisski_trails.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wisski_trails_config_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('wisski_trails.settings');

    $form['base_url'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Base URL'),
      '#description' => $this->t('The base URL for the iframe sources.'),
      '#default_value' => $config->get('base_url'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('wisski_trails.settings')
      ->set('base_url', $form_state->getValue('base_url'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
