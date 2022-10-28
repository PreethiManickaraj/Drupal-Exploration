<?php

namespace Drupal\UserRegistrationForm\src\Form\UserRegistrationForm\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class UserRegistrationForm extends FormBase {
  
    public function getFormId() {
        return 'UserRegistrationForm';
    }

    public function buildForm(array $form, FormStateInterface $form_state) {
        $form['student_name'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Enter Name:'),
          '#required' => TRUE,
        );
        $form['student_rollno'] = array(
          '#type' => 'textfield',
          '#title' => $this->t('Enter Enrollment Number:'),
          '#required' => TRUE,
        );
        $form['student_mail'] = array(
          '#type' => 'email',
          '#title' => $this->t('Enter Email ID:'),
          '#required' => TRUE,
        );
        $form['student_phone'] = array (
          '#type' => 'tel',
          '#title' => $this->t('Enter Contact Number'),
        );
        $form['student_dob'] = array (
          '#type' => 'date',
          '#title' => $this->t('Enter DOB:'),
          '#required' => TRUE,
        );
        $form['student_gender'] = array (
          '#type' => 'select',
          '#title' => ('Select Gender:'),
          '#options' => array(
            'Male' => $this->t('Male'),
            'Female' => $this->t('Female'),
            'Other' => $this->t('Other'),
          ),
        );
        $form['actions']['#type'] = 'actions';
        $form['actions']['submit'] = array(
          '#type' => 'submit',
          '#value' => $this->t('Register'),
          '#button_type' => 'primary',
        );
        return $form;
    }

    public function submitForm(array &$form, FormStateInterface $form_state) {
        \Drupal::messenger()->addMessage($this->t("Student Registration Done!! Registered Values are:"));
    }
}