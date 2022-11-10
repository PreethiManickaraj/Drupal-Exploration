<?php
namespace Drupal\customer_data\Controller;
 
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Drupal\Core\Database\Database;
 
/**
 * Provides route responses for the Example module.
 */
class CustomerDetailsController extends ControllerBase {
 
  /**
   * Returns a simple page.
   *
   * @return array
   *   A simple renderable array.
   */
  public function successpage() {
  //display thankyou page
    $element = array(
      '#markup' => 'Form data submitted',
    );
    return $element;
  }
 
  public function getDetails() {

    $result = \Drupal::database()->select('customers', 'n')
              ->fields('n', array('id', 'fname', 'sname', 'age', 'marks'))
              ->execute()->fetchAllAssoc('id');
      $rows = array();
      foreach ($result as $row => $content) {
        $rows[] = array(
          'data' => array($content->id, $content->fname, $content->sname, $content->age, $content->marks));
      }
      $header = array('Id', 'First name', 'Second name', 'Age', 'Marks');
      $output = array(
        '#theme' => 'grid',   
        '#header' => $header,
        '#rows' => $rows
      );
      return $output;
  }
}
