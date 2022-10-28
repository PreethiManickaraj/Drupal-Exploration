<?php
namespace Drupal\customer_register\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

/**
 * Class DisplayTableController.
 *
 * @package Drupal\mydata\Controller
 */
class DisplayTableController extends ControllerBase {

     public function getContent() {
    // First we'll tell the user what's going on. This content can be found
    // in the twig template file: templates/description.html.twig.
    // @todo: Set up links to create nodes and point to devel module.
    $build = [
      'description' => [
        '#theme' => 'mydata_description',
        '#description' => 'foo',
        '#attributes' => [],
      ],
    ];
    return $build;
  }

  /**
   * Display.
   *
   * @return string
   *   Return Hello string.
   */
  public function display() {
    /**return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: display with parameter(s): $name'),
    ];*/
    //create table header
    $header_table = array(
     'id'=>    $this->t('SrNo'),
      'name' => $this->t('Name'),
        'mobilenumber' => $this->t('MobileNumber'),
        'email'=>$this->t('Email'),
        'age' => $this->t('Age'),
        'gender' => $this->t('Gender'),
    );

//select records from table
    $query = \Drupal::database()->select('customer_register', 'm');
      $query->fields('m', ['id','name','mobilenumber','email','age','gender']);
      $results = $query->execute()->fetchAll();
        $rows=array();
    foreach($results as $data){
        $delete = Url::fromUserInput('/customer_register/form/delete/'.$data->id);
        $edit   = Url::fromUserInput('/customer_register/form/customer_register?num='.$data->id);

      //print the data from table
             $rows[] = array(
            'id' =>$data->id,
                'name' => $data->name,
                'mobilenumber' => $data->mobilenumber,
                //'email' => $data->email,
                'age' => $data->age,
                'gender' => $data->gender,

                 \Drupal::l('Delete', $delete),
                 \Drupal::l('Edit', $edit),
            );
}
    //display data in site
    $form['table'] = [
            '#type' => 'table',
            '#header' => $header_table,
            '#rows' => $rows,
            '#empty' => $this->t('No users found'),
        ];
        return $form;
}
}