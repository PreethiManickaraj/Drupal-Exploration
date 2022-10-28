<?php
namespace Drupal\customer_register\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * Class CustomerForm.
 *
 * @package Drupal\customer_register\Form
 */
class CustomerForm extends FormBase {
/**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'customer_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $conn = Database::getConnection();
     $record = array();
    if (isset($_GET['num'])) {
        $query = $conn->select('customer_register', 'm')
            ->condition('id', $_GET['num'])
            ->fields('m');
        $record = $query->execute()->fetchAssoc();
    }
    $form['name'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Customer Name:'),
      '#required' => TRUE,
       //'#default_values' => array(array('id')),
      '#default_value' => (isset($record['name']) && $_GET['num']) ? $record['name']:'',
      );
    $form['mobile_number'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Mobile Number:'),
      '#default_value' => (isset($record['mobilenumber']) && $_GET['num']) ? $record['mobilenumber']:'',
      );
    $form['customer_mail'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email ID:'),
      '#required' => TRUE,
      '#default_value' => (isset($record['email']) && $_GET['num']) ? $record['email']:'',
      );

    $form['customer_age'] = array (
      '#type' => 'textfield',
      '#title' => $this->t('AGE'),
      '#required' => TRUE,
      '#default_value' => (isset($record['age']) && $_GET['num']) ? $record['age']:'',
       );

    $form['customer_gender'] = array (
      '#type' => 'select',
      '#title' => ('Gender'),
      '#options' => array(
        'Female' => $this->t('Female'),
        'male' => $this->t('Male'),
        '#default_value' => (isset($record['gender']) && $_GET['num']) ? $record['gender']:'',
        ),
      );

    $form['submit'] = [
        '#type' => 'submit',
        '#value' => 'save',
        //'#value' => t('Submit'),
    ];
    return $form;
  }
  /**
    * {@inheritdoc}
    */
  public function validateForm(array &$form, FormStateInterface $form_state) {
         $name = $form_state->getValue('customer_name');
          if(preg_match('/[^A-Za-z]/', $name)) {
             $form_state->setErrorByName('customer_name', $this->t('your name must in characters without space'));
          }
        if (!intval($form_state->getValue('customer_age'))) {
             $form_state->setErrorByName('customer_age', $this->t('Age needs to be a number'));
            }
         /* $number = $form_state->getValue('candidate_age');
          if(!preg_match('/[^A-Za-z]/', $number)) {
             $form_state->setErrorByName('candidate_age', $this->t('your age must in numbers'));
          }*/
          if (strlen($form_state->getValue('mobile_number')) < 10 ) {
            $form_state->setErrorByName('mobile_number', $this->t('your mobile number must in 10 digits'));
           }
    parent::validateForm($form, $form_state);
  }
  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $field=$form_state->getValues();
    $name=$field['customer_name'];
    //echo "$name";
    $number=$field['mobile_number'];
    $email=$field['customer_mail'];
    $age=$field['customer_age'];
    $gender=$field['customer_gender'];
    if (isset($_GET['num'])) {
          $field  = array(
              'name'   => $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'age' => $age,
              'gender' => $gender,
          );
          $query = \Drupal::database();
          $query->update('customer_register')
              ->fields($field)
              ->condition('id', $_GET['num'])
              ->execute();
          drupal_set_message("succesfully updated");
          $form_state->setRedirect('customer_register.display_table_controller_display');
      }
       else
       {
           $field  = array(
              'name'   =>  $name,
              'mobilenumber' =>  $number,
              'email' =>  $email,
              'age' => $age,
              'gender' => $gender,
          );
           $query = \Drupal::database();
           $query ->insert('mydata')
               ->fields($field)
               ->execute();
           drupal_set_message("succesfully saved");
           $response = new RedirectResponse("/customer_register/hello/table");
           $response->send();
       }
     }
}