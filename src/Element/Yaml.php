<?php

namespace Drupal\yaml\Element;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element\FormElement;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml as SymfonyYaml;

/**
 * Class Yaml
 * @package Drupal\yaml\Element\Yaml
 *
 * @FormElement("yaml")
 */
class Yaml extends FormElement
{

  /**
   * Returns the element properties for this element.
   *
   * @return array
   *   An array of element properties. See
   *   \Drupal\Core\Render\ElementInfoManagerInterface::getInfo() for
   *   documentation of the standard properties of all elements, and the
   *   return value format.
   */
  public function getInfo() {
    $class = get_class($this);
    return array(
      '#input' => TRUE,
      '#cols' => 60,
      '#rows' => 5,
      '#resizable' => 'vertical',
      '#process' => array(
        array($class, 'processAjaxForm'),
      ),
      '#theme' => 'textarea',
      '#theme_wrappers' => array('form_element'),
      '#attributes' => array(
        'style' => 'font-family:monospace',
      ),
    );
  }

  /**
   * {@inheritdoc}
   */
  public static function valueCallback(&$element, $input, FormStateInterface $form_state) {
    try {
      if ($input === FALSE) {
        return SymfonyYaml::dump($element['#default_value']);
      }
      else {
        return isset($input) ? SymfonyYaml::parse($input) : null;
      }
    } catch (ParseException $e) {
      $form_state->setError($element, t('The YAML is not valid. %message', array('%message' => $e->getMessage())));
      return null;
    }
  }
}
