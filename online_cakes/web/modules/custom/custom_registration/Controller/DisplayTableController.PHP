<?php
namespace Drupal\custom_registration\Controller;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;

class DisplayTableController extends ControllerBase {
    public function getContent() {
        $build = [
            'description' => [
              '#theme' => 'custom_description',
              '#description' => 'foo',
              '#attributes' => [],
            ],
          ];
          return $build;
    }

    public function display() {
        $header_table = array(
         'id'=>    $this->t('SrNo'),
          'name' => $this->t('Name'),
        );
        $query = \Drupal::database()->select('custom_registration', 'm');
          $query->fields('m', ['id','name']);
          $results = $query->execute()->fetchAll();
            $rows=array();
        foreach($results as $data){
            $rows[] = array(
                'id' =>$data->id,
                'name' => $data->name,
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