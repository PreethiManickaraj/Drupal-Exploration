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
  //fetch data from employee table.
  $db = \Drupal::database(); 
  $query = $db->select('customers', 'n'); 
  $query->fields('n'); 
  $response = $query->execute()->fetchAll();
    return new JsonResponse( $response );
  }
}