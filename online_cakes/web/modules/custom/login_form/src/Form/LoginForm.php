<?php
/**
 * @file
 * Contains \Drupal\student_registration\Form\RegistrationForm.
 */
namespace Drupal\login_form\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

class LoginForm extends FormBase {

    public function getFormId() {
        return 'login_form';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['user_name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Enter Name:'),
          '#required' => TRUE,
        );

        $form['user_mail'] = array(
            '#type' => 'password',
            '#title' => $this->t('Enter password:'),
            '#required' => TRUE,
        );

        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
            '#type' => 'submit',
            '#value' => $this->t('Register'),
            '#button_type' => 'primary',
        );
        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state) {
        if(strlen($form_state->getValue('user_name')) < 8) {
          $form_state->setErrorByName('student_rollno', $this->t('Please enter characters more than 8'));
        }
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        $url = Url::fromUri('/loginForm');
        $form_state->setRedirectUrl($url);
        \Drupal::messenger()->addMessage($this->t("Login Successfull!!"));
    }
}