<?php
 
namespace Drupal\route_parameter\Controller;
 
use Drupal\Core\Access\AccessResult;
use Drupal\node\NodeInterface;
 
class DisplayNode {
 
  public function content(NodeInterface $node) {
    $element = array(
      '#markup' => $node->body->value,
    );
    return $element;
  }
 
  public function access(NodeInterface $node) {
    $user = \Drupal::currentUser();
    if ($node->getType() == 'article' && !in_array('authenticated', $user->getRoles())) {
      return AccessResult::forbidden();
    }
    return AccessResult::allowed();
  }
 
  public function getTitle(NodeInterface $node) {
    return $node->getTitle();
  }
 
}