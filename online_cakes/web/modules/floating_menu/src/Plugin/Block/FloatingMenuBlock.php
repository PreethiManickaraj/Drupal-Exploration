<?php

namespace Drupal\floating_menu\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormInterface;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Provides a menu block
 *
 * @Block(
 *   id = "floating_menu_block",
 *   admin_label = @Translation("Floating Menu"),
 * )
 */
class FloatingMenuBlock extends BlockBase implements BlockPluginInterface {
  /**
   * {@inheritdoc}
   */
  public function build() {
    $config = $this->getConfiguration();

    $max_items = $config['count_of_items'];
    if (empty($max_items)) {
      $max_items = 5;
    }

    $menu_item_variables = [];
    for ($i = 0; $i < $max_items; $i++) {

      $untranslated_url = $config['menu_item'][$i]['menu_item_target_url'];
      if (!empty($untranslated_url) && substr($untranslated_url, 0, 1) == '/') {
        $langManager = \Drupal::service('language_manager');
        $path = \Drupal::service('path.alias_manager')->getPathByAlias($untranslated_url, 'fi');
        $translated_url = \Drupal::service('path.alias_manager')->getAliasByPath($path, $langManager->getCurrentLanguage()->getId());
        if ($langManager->getCurrentLanguage()->getId() != $langManager->getDefaultLanguage()->getId()) {
          $translated_url = '/' . $langManager->getCurrentLanguage()->getId() . $translated_url;
        }
      }
      else {
        $translated_url = $untranslated_url;
      }

      if (!empty($config['menu_item'][$i]['menu_item_icon_url'])) {
        $menu_item_variables[] = [
          'popup_html' =>  [
            '#markup' => $config['menu_item'][$i]['menu_item_popup_html']['value'],
          ],
          'url' => $translated_url,
          'icon_url' => $config['menu_item'][$i]['menu_item_icon_url'],
        ];
      }
    }
    return array(
      '#theme' => 'floating_menu_block',
      '#attached' => array(
       'library' =>  array(
         'floating_menu/floating-menu'
        ),
      ),
      '#menu_items' => $menu_item_variables,
    );
  }
  /**
   * {@inheritdoc}
   */
  public function blockForm($form, FormStateInterface $form_state) {
    $form = parent::blockForm($form, $form_state);
    $config = $this->getConfiguration();

    $form['count_of_items'] = [
      '#type' => 'number',
      '#title' => t('Menu item count'),
      '#description' => t('Select how many items to show in the menu. Save the form and enter details.'),
      '#default_value' => $config['count_of_items'],
    ];

    $form['menu_items'] = [
      '#type' => 'fieldset',
      '#title' => t('Menu Items'),
      '#prefix' => '<div id="menu-item-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    $max_items = $config['count_of_items'];
    if (empty($max_items)) {
      $max_items = 5;
    }

    for ($i = 0; $i < $max_items; $i++) {
      $form['menu_items']['menu_item_'. $i] = [
       '#type' => 'fieldset',
       '#title' => t('Menu Item '. ($i + 1)),
       '#tree' => TRUE,
      ];
      $form['menu_items']['menu_item_' . $i]['menu_item_popup_html'] = [
        '#type' => 'text_format',
        '#format' => 'full_html',
        '#title' => $this->t('Menu Item Popup HTML'),
        '#default_value' => !empty($config['menu_item']) ? $config['menu_item'][$i]['menu_item_popup_html']['value'] : '',
      ];
      $form['menu_items']['menu_item_' . $i]['menu_item_target_url'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Menu Item Target URL'),
        '#default_value' => !empty($config['menu_item']) ? $config['menu_item'][$i]['menu_item_target_url'] : '',
      ];
      $form['menu_items']['menu_item_' . $i]['menu_item_icon'] = [
        '#type' => 'managed_file',
        '#title' => t('Menu Item Icon'),
        '#upload_validators' => array(
            'file_validate_is_image' => array(),
            'file_validate_extensions' => array('jpg jpeg png gif'),
          ),
        '#upload_location' => 'public://',
        '#default_value' => !empty($config['menu_item']) ? [$config['menu_item'][$i]['menu_item_icon_file_id']] : '',
      ];
    }
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function blockSubmit($form, FormStateInterface $form_state) {
    $form_state_values = $form_state->getValues();
    $this->configuration['count_of_items'] = $form_state_values['count_of_items'];
    $this->configuration['menu_item'] = [];
    foreach($form_state_values['menu_items'] as $key => $value) {
      $file = File::load( $value['menu_item_icon'][0] );
      if (!empty($file)) {
        $file->setPermanent();
        $file->save();
        $image_url = file_create_url($file->getFileUri());
      }
      else {
        $image_url = NULL;
      }
      $this->configuration['menu_item'][] = [
        'menu_item_popup_html' => $value['menu_item_popup_html'],
        'menu_item_target_url' => $value['menu_item_target_url'],
        'menu_item_icon_file_id' => intval($value['menu_item_icon'][0]),
        'menu_item_icon_url' => $image_url,
      ];
    }
  }
}
